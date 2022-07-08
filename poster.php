	<?php
	error_reporting(-1);
	ini_set('display_errors', 'On');


  session_start();

     if(isset($_SESSION['email'])){



    }else{
    header('location: index.php?');

    }



include('includes/db.php');
$q = "SELECT * FROM users WHERE email = ?";
                $req = $db->prepare($q);
                $req->execute([
                    $_SESSION['email']

                ]);

                $user = $req->fetch();
								if(isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
									// Un fichier a été envoyé

									// Vérifier le type de fichier
									$acceptable = [
										'image/jpeg',
										'image/png',
										'image/gif'
									];

									if(!in_array($_FILES['image']['type'], $acceptable)){
										// Redirection vers poster.php
										header('location: poster.php?message=Format de fichier incorrect.&type=danger');
										exit;
									}

									// Vérifier le poids du fichier

									$maxSize = 2 * 1024 * 1024; // 2Mo

									if($_FILES['image']['size'] > $maxSize){
										// Redirection vers poster.php
										header('location: poster.php?message=Ce fichier est trop lourd !&type=danger');
										exit;
									}

									// Chemin vers le dossier d'uploads
									$path = 'publicationimage';

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




if(isset($_POST['article_contenu'])) {
   if(isset($_POST['ville']) && !empty($_POST['ville']) && !empty($_POST['article_contenu']) && isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
		 // Un fichier a été envoyé

		 // Vérifier le type de fichier
		 $acceptable = [
			 'image/jpeg',
			 'image/png',
			 'image/gif'
		 ];

		 if(!in_array($_FILES['image']['type'], $acceptable)){
			 // Redirection vers poster.php
			 header('location: poster.php?message=Format de fichier incorrect.&type=danger');
			 exit;
		 }

		 // Vérifier le poids du fichier

		 $maxSize = 10 * 1024 * 1024; // 2Mo

		 if($_FILES['image']['size'] > $maxSize){
			 // Redirection vers poster.php
			 header('location: poster.php?message=Ce fichier est trop lourd !&type=danger');
			 exit;
		 }

		 // Chemin vers le dossier d'uploads
		 $path = 'publicationimage';

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


      $descriptionpublication = htmlspecialchars($_POST['article_contenu']);
			$ville = htmlspecialchars($_POST['ville']);
      $ins = $db->prepare('INSERT INTO publication ( descriptionpublication,idauteur,datepublication,imagepublication,localisation) VALUES (?, ?, NOW(),?,?)');
      $ins->execute(array($descriptionpublication,$user['id'],$filename,$ville));
      $message = 'Votre publication a bien été posté';
   } else {
      $message = 'Veuillez remplir tous les champs';
   }
}
?>
<?php

?>
<!DOCTYPE html>
<html>
<head>
  <?php include('includes/head.php'); ?>
	<style media="screen">
		footer{
			margin-top: 90px;
		}
	</style>

</head>
<body>

<?php include('includes/header2.php'); ?>

			<main>

    <?php if(isset($message)) { echo '<center><h2>' .$message. '</center></h2>'; } ?>


 				 	<div class="inscrip">

 			       <form method="POST" class="" enctype="multipart/form-data">
 							 <h2>Poster</h2><br>
 							 <label>Votre photo:</label><br>
 						<input type="file" name="image" accept="image/jpeg,image/gif,image/png,image/jpg">
 						<br><br>
 			      <textarea name="article_contenu" class="form-control text1" id="subject" type="text" rows="3" placeholder="Description"></textarea>
 						<input type="text" name="ville" placeholder="adresse précise ex:54 avenue des poulets,Paris,France,Europe">
 			      <input type="submit" value="Envoyer la publication" >
 			      </form>
 					</div>
 			</main>


		<?php include('includes/footer.php'); ?>

</body>




</html>
