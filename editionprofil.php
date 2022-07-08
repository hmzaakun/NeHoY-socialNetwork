<?php
session_start();
include('includes/db.php');

if(isset($_SESSION['email'])){
  $q = "SELECT * FROM users WHERE email = ?";
                $req = $db->prepare($q);
                $req->execute([
                    $_SESSION['email']

                ]);
                 $user = $req->fetch();

}
  if(isset($_POST['nouveau_pseudo']) && !empty($_POST['nouveau_pseudo']) ){
    $newpseudo = htmlspecialchars($_POST['nouveau_pseudo']);
    $insertpseudo = $db->prepare("UPDATE users SET pseudo = ? WHERE email = ?");
    $insertpseudo->execute(array($newpseudo, $_SESSION['email']));
      header('location: monprofil.php?');


  }

  if(isset($_POST['nouveau_nom']) && !empty($_POST['nouveau_nom']) ){
    $nouveau_nom = htmlspecialchars($_POST['nouveau_nom']);
    $insertnom = $db->prepare("UPDATE users SET nom = ? WHERE email = ?");
    $insertnom-> execute(array($nouveau_nom,  $_SESSION['email']));
      header('location: monprofil.php?');

  }

  if(isset($_POST['nouveau_prenom']) && !empty($_POST['nouveau_prenom']) ){
    $nouveau_prenom = htmlspecialchars($_POST['nouveau_prenom']);
    $insertprenom = $db->prepare("UPDATE users SET prenom = ? WHERE email = ?");
    $insertprenom-> execute(array($nouveau_prenom,  $_SESSION['email']));
    header('location: monprofil.php?');

  }
  if(isset($_POST['nouveau_bio']) && !empty($_POST['nouveau_bio']) ){
    $nouveau_bio = htmlspecialchars($_POST['nouveau_bio']);
    $insertbio = $db->prepare("UPDATE users SET bio = ? WHERE email = ?");
    $insertbio-> execute(array($nouveau_bio,  $_SESSION['email']));
    header('location: monprofil.php?');

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
  	$array = explode('.', $filename);
  	$ext = end($array);

  	$filename = 'image-' . time() . '.' . $ext;

  	$destination = $path . '/' . $filename;
  	$tempName = $_FILES['image']['tmp_name'];
  	move_uploaded_file($tempName, $destination);
    //'image' => isset($filename) ? $filename : 'noimage'
    $insertimage = $db->prepare("UPDATE users SET image= ? WHERE email = ?");
    $insertimage-> execute([
      isset($filename) ? $filename : 'noimage',  $_SESSION['email']
  ]);

  }



?>


<!DOCTYPE html>
<html>
<head>
  <?php include('includes/head.php'); ?>
</head>

<body>

  <?php include('includes/header2.php'); ?>

  <main>
  <div class="inscrip">
      <form method="POST" action="" enctype="multipart/form-data">
        <h2>Edition de mon profil</h2><br>
        <label>Pseudo :</label>
      <input type="text" name="nouveau_pseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>" />
        <label>Nom :</label>
      <input type="text" name="nouveau_nom" placeholder="Nom" value="<?php echo $user['nom']; ?>" />
        <label>Prenom :</label>
      <input type="text" name="nouveau_prenom" placeholder="Prénom" value="<?php echo $user['prenom']; ?>" />
        <label>Bio :</label>
      <input type="text" name="nouveau_bio" placeholder="bio" value="<?php echo $user['bio']; ?>" />
      <label>Avatar :</label><br>
      <input type="file"  name="image" accept="image/jpeg,image/gif,image/png,image/jpg">
      <input type="submit" value="Mettre à jour mon profil">


    </form>
  </div>
</main>


<?php include('includes/footer.php'); ?>
</body>

</html>
