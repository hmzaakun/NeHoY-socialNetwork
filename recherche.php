<?php
error_reporting(-1);
ini_set('display_errors', 'On');
session_start();
  $i=0;
include('includes/db.php');
if(isset($_POST['recherchelocalisation'])){

  $i=1;
$mot = htmlentities($_POST['recherchelocalisation'], ENT_QUOTES, 'UTF-8');
 //   htmlentities($_POST['recherchepseudo'], ENT_QUOTES, 'UTF-8'); contre les faillessql

$recherche = "SELECT * FROM publication INNER JOIN users WHERE localisation LIKE '%$mot%' AND idauteur=id";
$req = $db->prepare($recherche);
$req->execute();
$rechercheuserbyid = $req->fetchAll();
if ($rechercheuserbyid==null){
  header('location: recherchetest.php?');
}

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
    <div class="">
          <div class="recherche1">
            	<h3>Recherche d'utilisateur</h3><br>
            	<input type="text" onkeyup="imu(this.value)" placeholder="recherche utilisateur">

            	<div class="recherche" id="content" >Vide</div>

            	<script type="text/javascript">
            		let content =  document.getElementById('content');

            		function imu(x){
            			if (x.length == 0){
            				content.innerHTML = 'Aucun utilisateur..'
            			}else{
            				var XML = new XMLHttpRequest();
            				XML.onreadystatechange = function(){
            					if (XML.readyState == 4 && XML.status == 200){
            						content.innerHTML = XML.responseText;
            					}
            				};
            				XML.open('GET','recherche_utilisateur.php?user='+x,true);
            				XML.send();
            			}
            		}
            	</script>
      </div>
    </div>



      <?php if ($i==0){ ?>
        <div class="recherche1">

        <form method="POST" action="">
          <h3>Recherche de publication</h3>
            <input type="text" name="recherchelocalisation"  placeholder="recherche par ville"  required="required">
            <input type="submit" value="Recherche">
          </form>
          </div>
        <?php // idpublication,descriptionpublication,datepublication,imagepublication,localisation,idauteur
       }else{ foreach ($rechercheuserbyid as $bg){
          ?>  <div class="filactu">
             <div class="filactu1">
               L'Auteur de ce post: <?php echo'<a href="profil.php?id=' .$bg['id'].' "> '. $bg['pseudo'] .' </a>' ?>
           <?php echo '<img src="publicationimage/' . $bg['imagepublication']. ' "  width="" height="" alt="publication">' ; ?>
            </div>
            <p>
              <h6><?php echo '<a href="https://www.google.fr/maps/place/' .$bg['localisation'].' "> <h4>'. $bg['localisation'] .' </h4> </a>'; ?></h6>
            </p>
         <p>
            <?php echo $bg['descriptionpublication']; ?>
              <a href="publication.php?publication=<?=$bg['idpublication']; ?>">Plus d'infos</a>
         </p>
         <p>
           Localisation : <?php echo $bg['localisation']; ?>
         </p>
         <p>
            <?php echo   strftime("%A %d %B %G %H:%M", strtotime($bg['datepublication']));  ?>
         </p>
         </div>
         <?php

        }} ?>
  </main>
    <?php include('includes/footer.php'); ?>




</body>

</html>
