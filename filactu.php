<!DOCTYPE html>
<?php error_reporting(-1);
      ini_set('display_errors', 'On');



      ?>

<html lang="fr" dir="ltr">
  <head>
    <?php include('includes/head.php'); ?>
  </head>
  <body>
    <?php include('includes/header.php'); ?>
    <?php
    if (isset($_SESSION['email']) AND !empty($_SESSION['email'])){
        $q = "SELECT * FROM users WHERE email = ?";
        $req = $db->prepare($q);
        $req->execute([
        $_SESSION['email']
        ]);
        $usersession = $req->fetch();

        $toutpublication = "SELECT * FROM abonne INNER JOIN publication,users WHERE idabonnement= ? AND idabonne=idauteur AND idauteur=id ORDER BY datepublication DESC";
         $reqos = $db->prepare($toutpublication);
         $reqos->execute([
           $usersession['id']
         ]);
         $affichepublication = $reqos->fetchAll();
    }
     ?>
      <main>



           <?php
           if (isset($_SESSION['email']) AND !empty($_SESSION['email'])){
           foreach ($affichepublication as $p) {?>
                   <div class="filactu">
                     <div class="filactu1">
                   <?php echo '<img src="publicationimage/' . $p['imagepublication']. ' "  width="" height="" alt="publication">' ; ?>
                    </div>
                  <p>
                    <?php echo '<a href="profil.php?id=' .$p['id'].' "> <h4>'. $p['pseudo'] .' </h4> </a>'; ?>
                  </p>
                  <p>
                      <a href="publication.php?publication=<?=$p['idpublication']; ?>">Plus d'infos</a>

                  </p>
                 <p>
                    <?php echo $p['descriptionpublication']; ?>
                 </p>


                 <p>
                    <?php echo   strftime("%A %d %B %G %H:%M", strtotime($p['datepublication']));  ?>
                 </p>
                 </div>
               <?php }}else{
                 echo "<center><h1>CONNECTES TOI</h1></center>";
               } ?>



      </main>
    <?php include('includes/footer.php'); ?>
  </body>
</html>
