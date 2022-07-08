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

          $toutpublication = "SELECT id,pseudo,idauteur,imagepublication,idpublication,datepublication,descriptionpublication FROM publication INNER JOIN users WHERE idauteur=id  EXCEPT SELECT id,pseudo,idauteur,imagepublication,idpublication,datepublication,descriptionpublication FROM abonne INNER JOIN publication,users WHERE idabonnement= ? AND idabonne=idauteur AND idauteur=id OR id=?  ORDER BY datepublication DESC";
           $reqos = $db->prepare($toutpublication);
           $reqos->execute([
             $usersession['id'],$usersession['id']
           ]);
           $affichepublication = $reqos->fetchAll();


      }
      $toutpublication2 = "SELECT * FROM publication INNER JOIN users WHERE idauteur=id ORDER BY datepublication DESC";
       $reqas = $db->prepare($toutpublication2);
       $reqas->execute();
       $affichepublication2 = $reqas->fetchAll();
       ?>


        <main>
          <div class="decouvrir">
          <?php
          if (isset($_SESSION['email']) AND !empty($_SESSION['email'])){
          foreach ($affichepublication as $d){?>

                    <div class="decouvrir1">
                  <?php echo '<img src="publicationimage/' . $d['imagepublication']. ' "  width="200px" height="200px" alt="publication">' ; ?>

                 <p>
                    <?php echo '<a href="profil.php?id=' .$d['id'].' "> <h4>'. $d['pseudo'] .' </h4> </a>'; ?>
                 </p>
                 <p>
                     <a href="publication.php?publication=<?=$d['idpublication']; ?>">Plus d'infos</a>

                 </p>
                <p>
                   <?php echo $d['descriptionpublication']; ?>
                </p>
                <p>
                   <?php echo   strftime("%A %d %B %G %H:%M", strtotime($d['datepublication']));  ?>
                </p>
                </div>
              <?php }} else{
                foreach ($affichepublication2 as $p) {?>

                            <div class="decouvrir1">
                          <?php echo '<img src="publicationimage/' . $p['imagepublication']. ' "  width="200px" height="200px" alt="publication">' ; ?>

                         <p>
                          <?php echo '<a href="profil.php?id=' .$p['id'].' "> <h4>'. $p['pseudo'] .' </h4> </a>'; ?>
                         </p>
                        <p>
                           <?php echo $p['descriptionpublication']; ?>
                        </p>
                        <p>
                            <a href="publication.php?publication=<?=$p['idpublication']; ?>">Plus d'infos</a>

                        </p>
                        <p>
                           <?php echo   strftime("%A %d %B %G %H:%M", strtotime($p['datepublication']));  ?>
                        </p>
                        </div>
                      <?php }}?></div>



        </main>
      <?php include('includes/footer.php'); ?>
  </body>
</html>
