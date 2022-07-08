<!DOCTYPE html>
<?php
error_reporting(-1);
      ini_set('display_errors', 'On');
      session_start();
include('includes/db.php');
    if(isset($_SESSION['email'])){
    }else{
    header('location: index.php?');
    }?>


<html>
    <head>
        <?php include('includes/head.php'); ?>

    </head>
    <body>
    <?php include('includes/header2.php'); ?>

        <main>
          <?php if (isset($_SESSION['email'])){
                $q = "SELECT * FROM users WHERE email = ?";
                $req = $db->prepare($q);
                $req->execute([
                    $_SESSION['email']
                ]);
              }
                $user = $req->fetch();
                // NOMBRE ABONNEMENT
                $nombreabonnement="SELECT COUNT(*) FROM abonne WHERE  idabonnement = ?";
                $reqzad = $db->prepare($nombreabonnement);
                $reqzad->execute([
                $user['id']

                ]);
                $numberabon = $reqzad->fetch();
                // NOMBRE ABONNE
                $nombreabo="SELECT COUNT(*) FROM abonne WHERE  idabonne = ?";
                $reqzada = $db->prepare($nombreabo);
                $reqzada->execute([
                $user['id']

                ]);
                $numberabo = $reqzada->fetch();

                ?>

                  <?php if($user) { ?>
                     <div class="media profil">
                         <?php echo '<img src=" uploads/' . $user['image'] . ' " id=avatar width="80" height="80" alt="Profil" class="align-self-start mr-3">';?>
                         <div class="media-body">
                           <h5 class="mt-0"><?php echo ' '  . $user['pseudo']. '';?></h5>
                              <?php echo '<a href="editionprofil.php?">Editer mon profil</a></br>';?>
                           <p>
                             <?php echo '<a href="gensabonne.php?id='.$user['id'].'">Abonné(s)</a> : ' . $numberabo[0]. ' || ' ;?>
                             <?php echo '<a href="gensabonnement.php?id='.$user['id'].'"> Abonnement(s</a>) : ' . $numberabon[0]. '' ;?>
                           </p>
                           <p>
                             <?php echo ' Nom : ' . $user['nom'] . ' || '; ?>
                             <?php echo ' Prenom : ' . $user['prenom'] . ''; ?>
                           </p>
                           <p>
                             <?php echo ' Mail : ' . $user['email'] . ''; ?>
                           </p>
                           <p>
                             <?php echo ' Bio : ' . $user['bio'] . '</p> '; ?>
                           </p>

                          <?php $toutpublication = "SELECT * FROM publication WHERE idauteur = ?";
                           $reqos = $db->prepare($toutpublication);
                           $reqos->execute([
                           $user['id']

                           ]);
                           if (isset($_GET['supprimer']) AND !empty($_GET['supprimer'])) {
                             $postsupprime=$_GET['supprimer'];
                           $supprime = "DELETE FROM publication WHERE idpublication = ? AND idauteur = ? ";
                           $requete = $db->prepare($supprime);
                         $requete->execute([
                           $postsupprime,$user['id']
                         ]);
                         header("Location:monprofil.php");

                       }
                       ?>






                         </div>
                     </div>

                     <div class="profil2">
                             <?php $allpublication = $reqos->fetchAll();
                             foreach ($allpublication as $p) {?>
                               <div class="profil3">
                               <?php echo '<img src="publicationimage/' . $p['imagepublication']. ' "  width="200" height="200" alt="publication">' ; ?>
                             <p>
                                <?php echo $p['descriptionpublication']; ?>
                             </p>
                             <p>
                                <?php echo   strftime("%A %d %B %G %H:%M", strtotime($p['datepublication']));  ?>
                                  <a href="publication.php?publication=<?=$p['idpublication']; ?>">Plus d'infos</a>
                             </p>
                             <a href="monprofil.php?supprimer=<?php echo $p['idpublication'];?>">Supprimer</a>
                             </div>
                             <?php } ?>
                     </div>
                     <?php } else {
                         echo '<center><h2>Utilisateur introuvable !</h2></center>';
                     }?>


        </main>

             <?php include('includes/footer.php'); ?>

    </body>
</html>
