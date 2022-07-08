<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require "PHPMailer/PHPMailerAutoload.php";



session_start();
$ip = $_SERVER['REMOTE_ADDR'];
if(isset($_POST['captcha'])){
    if($_POST['captcha'] == $_SESSION['captcha']){


    }
        else{
        header('location: inscription.php?message=Captcha invalide ...');
        exit;
    }
}


if( !isset($_POST['email']) || empty($_POST['email']) ||
 !isset($_POST['password']) || empty($_POST['password']) ||
  !isset($_POST['pseudo']) || empty($_POST['pseudo']) ||
    !isset($_POST['nom']) || empty($_POST['nom']) ||
      !isset($_POST['datedenaissance']) || empty($_POST['datedenaissance']) ||
        !isset($_POST['datedenaissance']) || empty($_POST['datedenaissance']) ||
         !isset($_POST['captcha']) || empty($_POST['captcha']) ||
          !isset($_POST['password2']) || empty($_POST['password2'])  ){
	header('location: inscription.php?message=Vous devez  tout remplir.');

	exit;
}


$password=$_POST['password'];
$password2=$_POST['password2'];


if ($password==$password2) {

			}else{
				header('location: inscription.php?message=Vos mot de passe ne correspondent pas.&type=danger');
				exit;
			}




if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){

	header('location: inscription.php?message=Email invalide&type=danger');
	exit;
}





if(isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
	// Un fichier a été envoyé

	// Vérifier le type de fichier
	$acceptable = [
		'image/jpeg',
		'image/png',
		'image/gif'
	];

	if(!in_array($_FILES['image']['type'], $acceptable)){
		// Redirection vers inscription.php
		header('location: inscription.php?message=Format de fichier incorrect.&type=danger');
		exit;
	}

	// Vérifier le poids du fichier

	$maxSize = 2 * 1024 * 1024; // 2Mo

	if($_FILES['image']['size'] > $maxSize){
		// Redirection vers inscription.php
		header('location: inscription.php?message=Ce fichier est trop lourd !&type=danger');
		exit;
	}

	// Chemin vers le dossier d'uploads
	$path = 'uploads';

	// Si le dossier n'existe pas, le créer
	if(!file_exists($path)){
		mkdir($path, 0777);
	}

	// Enregistrement du fichier à son emplacement définitif

	$filename = $_FILES['image']['name'];

	// Renomer le fichier en fonction de la date (timestamp)
	// Note : ne marche aps si 2 fichiers avec la meme extention sont chargés dans la même seconde

	// Récupérer l'extention du fichier


	$array = explode('.', $filename); // convertir en tableau découper par les points
	$ext = end($array); // Récupérer la derniere variable du tableau

	// Créer un nom d'image du type image-1666123.ext

	$filename = 'image-' . time() . '.' . $ext;

	$destination = $path . '/' . $filename;
	$tempName = $_FILES['image']['tmp_name'];
	move_uploaded_file($tempName, $destination);



}






// ajout d'un nouvel utilisateur à la table

// Connexion à la base de données
include('includes/db.php');

$email = htmlspecialchars($_POST['email']);
$pseudo =htmlspecialchars($_POST['pseudo']);
$nom =htmlspecialchars($_POST['nom']);
$prenom =htmlspecialchars($_POST['prenom']);
$datedenaissance =$_POST['datedenaissance'];

$q = "SELECT id FROM users WHERE email = :email";
// Préparation de la db
$req = $db->prepare($q);
// Execution de la requête
$req->execute([
	'email' => $email
]);
// Récupération de la première ligne de résultats
$resultat = $req->fetch(); // Renvoie un tableau représentant la première ligne de résultats ou un booléen FALSE
// Si existe => erreur, redirection
if($resultat){
	// Redirection vers inscription.php
	header('location: inscription.php?message=Cet email est déjà utilisé.&type=danger');
	exit;
}
$q = "SELECT id FROM users WHERE pseudo = :pseudo";

$req = $db->prepare($q);

$req->execute([
    'pseudo' => $pseudo
]);

$resultat = $req->fetch();

if($resultat){

    header('location: inscription.php?message=Ce pseudo est déjà utilisé.&type=danger');
    exit;
}

$cle = rand(1000000, 9000000);


$q = "INSERT INTO users (email, password, image, pseudo, nom, prenom, datedenaissance, cle , confirme,ip) VALUES (:email, :password, :image, :pseudo, :nom, :prenom, :datedenaissance, :cle , :confirme,:ip)";
$req = $db->prepare($q); // Préparation de la requête
$reponse = $req->execute([
	'email' => $email,
	'password' => hash('sha512', $password),
    'image' => isset($filename) ? $filename : 'noimage',
		'pseudo' => $pseudo,
		'nom' => $nom,
		'prenom' =>$prenom,
		'datedenaissance' => $datedenaissance,
    'cle' => $cle,
    'confirme' => 0,
    	'ip' => $ip


]);
$qaa = "SELECT * FROM users WHERE email = ?";
$reqrr = $db->prepare($qaa);
$reqrr->execute([$email]);
$userinfo = $reqrr->fetch();
$_SESSION['id']=$userinfo['id'];
function smtpmailer($to, $from, $from_name, $subject, $body)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = 'nhorizony@gmail.com';
        $mail->Password = 'mailduprojet94';

   //   $path = 'reseller.pdf';
   //   $mail->AddAttachment($path);

        $mail->IsHTML(true);
        $mail->From='nhorizony@gmail.com';
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send())
        {
            $error ="Succés";
            return $error;
        }
        else
        {
            $error = "Erreur";
            return $error;
        }

    }

    $to   = $_POST['email'];
    $from = 'nhorizony@gmail.com';
    $name = 'NeHoY';
    $subj = 'Emailconfirmation';
    $msg = '<a href="http://51.91.157.145/verif.php?id='.$_SESSION['id'].'&cle='.$cle.'">Confirmer son compte';
    $error=smtpmailer($to,$from, $name ,$subj, $msg);
 if($reponse){



	header('location: monprofil.php?message=Compte créé avec succès !!&type=success');
	exit;
}else{

	header('location: inscription.php?message=Erreur lors de la création du compte.&type=danger');
	exit;
}
