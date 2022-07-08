<?php
// Récupérer et traiter les informations envoyées par le formulaire via la methode post
//$_POST['email']
//$_POST['password']



// Vérifier que les champs ne sont pas vides

if( !isset($_POST['pseudo']) || empty($_POST['pseudo']) || !isset($_POST['pseudo']) || empty($_POST['pseudo']) ){
	// Redirection vers connexion.php
	header('location: admin.php?message=Vous devez remplir les 2 champs&type=danger');
	exit;
}

// Ecrire une ligne dans le fichier log.txt

// Ouvrir le fichier log (ou le créer si besoin)
$log = fopen('log.txt', 'a+');

// Création de la ligne à ajouter


// Ecrire la ligne dans le fichier
fputs($log, $line);

// Fermeture du fichier
fclose($log);








// Vérifier que l'utilisateur existe en base de données

//Connexion à la base de données.
include('includes/db.php');

$q = "SELECT * FROM users WHERE pseudo = :pseudo AND password = :password AND role LIKE 'admin' ";
$req = $db->prepare($q);
$req->execute([
	'pseudo' => htmlspecialchars($_POST['pseudo']),
	'password' => hash('sha512', $_POST['password']) // Même méthode de hachage qu'à la création de l'utilisateur
]);

$adminusers = $req->fetch(); // Récupérer la première ligne de résultat // false si aucun résultat

if($adminusers){
	// la requête a renvoyé un résultat
	// ouvrir une session utilisateur
	session_start();

	// Remplir la session
	$_SESSION['pseudo'] = htmlspecialchars($_POST['pseudo']);
		$_SESSION['email'] = $adminusers['email'];

	// Redirection vers la page d'accueil
	header('location: adminedit.php');
	exit;
}else{
	// la requête n'a renvoyé aucun résultat
	// Redirection vers connexion.php
	header('location: admin.php?message=Identifiants invalides&type=danger');
	exit;
}















?>
