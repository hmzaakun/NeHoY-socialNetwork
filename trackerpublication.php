<!DOCTYPE html>
<?php

  error_reporting(-1);
  ini_set('display_errors', 'On');

  session_start();
    include('includes/db.php');
    $verificationadmin = "SELECT * FROM users WHERE email = ?";
                     $verificationadmin = $db->prepare($verificationadmin);
                     $verificationadmin->execute([
                        $_SESSION['email']

                    ]);

                     $verificationadmin =  $verificationadmin->fetch();


    if(isset($_SESSION['email']) AND $verificationadmin['role']=='admin' ){



   }else{
   header('location: index.php?');

   }
   if(isset($_GET['id'])){
   $getid=$_GET['id'];
 }else{
   header('location:adminedit.php?');
 }

    ?>

<html>
  <head>
    <?php include('includes/head.php'); ?>
  </head>

  <body>
    <?php include('includes/header2.php'); ?>
    <main>
      <div class= "track1"><button ><a type="button" href="tracker.php?id=<?php echo $getid;?>">retour</a></button>
      </div>
      <div class="decouvrir">
    <?php

      $q = "SELECT * FROM users WHERE id = ?";
      $req = $db->prepare($q);
      $req->execute([
      $getid

      ]);
      $user = $req->fetch();

    $publicationdumec = "SELECT * FROM publication INNER JOIN users WHERE id=idauteur AND id=? ORDER BY datepublication ASC ";
    $publicationall = $db->prepare($publicationdumec);
    $publicationall->execute([
   $getid
    ]);
  $publicationall = $publicationall->fetchAll();
    if (isset($_GET['supprimepubli']) AND !empty($_GET['supprimepubli']) AND isset($_SESSION['email']) AND !empty($_SESSION['email']) ) {
      $idpubli=$_GET['supprimepubli'];
    $supprimepubli = "DELETE FROM publication WHERE idpublication =  $idpubli ";
    $supprimepublication = $db->prepare($supprimepubli);
  $deletepubliation=$supprimepublication->execute();
  header('location:trackerpublication.php?id='.$user['id']);
      }

      foreach (  $publicationall as $d) {?>
        <div class="decouvrir1">
      <?php echo '<img src="publicationimage/' . $d['imagepublication']. ' "  width="200px" height="200px" alt="publication">' ; ?>

     <p>
        <?php echo '<a href="profil.php?id=' .$d['id'].' "> <h4>'. $d['pseudo'] .' </h4> </a>'; ?>
     </p>
     <p>
         <a href="publication.php?publication=<?=$d['idpublication']; ?>">Plus d'infos</a><br>
         <a href="trackerpublication.php?id=<?php echo  $user['id'];?>&supprimepubli=<?php echo  $d['idpublication'];?>">Supprimer</a>

     </p>
    <p>
       <?php echo $d['descriptionpublication']; ?>
    </p>
    <p>
       <?php echo   strftime("%A %d %B %G %H:%M", strtotime($d['datepublication']));  ?>
    </p>
    </div>
      <?php } ?>

</div>
</main>
  <?php include('includes/footer.php'); ?>
  </body>
</html>
