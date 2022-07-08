<?php
      error_reporting(-1);
      ini_set('display_errors', 'On');
      session_start();

      include('includes/db.php');


// on prend les infos de la session
        if (isset($_SESSION['email'])){
            $q = "SELECT * FROM users WHERE email = ?";
            $req = $db->prepare($q);
            $req->execute([
            $_SESSION['email']
            ]);
            $usersession = $req->fetch();
        }
//
?>
<!DOCTYPE html>
<html>
    <head>
    <?php include('includes/head.php'); ?>
    </head>
          <body>
                <?php include('includes/header2.php'); ?>
                <main>

                <?php
                  // info du GET ID
                  if (!isset($_GET['id']) AND empty($_GET['id'])){
                    header('Location:recherche.php');
                  }
                      $getid=$_GET['id'];

                      $q = "SELECT * FROM users WHERE id = ?";
                      $req = $db->prepare($q);
                      $req->execute([
                      $getid

                      ]);
                      $user = $req->fetch();
                  //


                // grab le nombre
                if (isset($_SESSION['email'])){
                      $selecttoutabonne="SELECT COUNT(*) FROM abonne WHERE idabonne = ? AND idabonnement = ?";
                      $reqza = $db->prepare($selecttoutabonne);
                      $reqza->execute([
                      $user['id'],$usersession['id']

                      ]);



                      $selecttoutabonne = $reqza->fetch();
                      }
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

                //
                if(isset($_POST['unfollow'])){
                    $deleteabo="DELETE FROM abonne WHERE idabonne = ? AND idabonnement = ?";
                    $reqouz = $db->prepare($deleteabo);
                    $deleteabonnement=$reqouz->execute([$user['id'],$usersession['id']]);
                     header("Refresh:0");
                    }
                if(isset($_POST['follow'])){
                    $prepareabo="INSERT INTO abonne (idabonnement,idabonne,dateabonnement) VALUES (?,?,NOW())";
                    $reqaz = $db->prepare($prepareabo);
                    $abonnement=$reqaz->execute([$usersession['id'],$user['id']]);
                     header("Refresh:0");
                    }

                if(isset($_GET['like']) AND isset($_SESSION['email']) ){

                    // 	idlike	liketime	idliker	publicationid
                    // INSERT INTO `likepublication` (idliker,idpublication,date) VALUES (1,13,NOW());
                    $preparelike="INSERT INTO liked (idliker,publicationid,liketime) VALUES (?,?,NOW())";
                    $reqaz = $db->prepare($preparelike);
                    $like=$reqaz->execute([$usersession['id'],$_GET['like']]);

                    }

                  if(isset($_GET['dislike']) AND isset($_SESSION['email']) ){

                      // 	idlike	liketime	idliker	publicationid
                      // INSERT INTO `likepublication` (idliker,idpublication,date) VALUES (1,13,NOW());
                      $preparedislike="DELETE FROM liked WHERE idliker = ? AND publicationid = ?";
                      $reqaz = $db->prepare($preparedislike);
                      $dislike=$reqaz->execute([$usersession['id'],$_GET['dislike']]);

                      }

                    if (isset($_SESSION['email']) AND !empty($_SESSION['email'])) {
                        $followback1 = "SELECT COUNT(*) FROM abonne WHERE idabonne = ? AND idabonnement = ?";
                        $fol1 = $db->prepare($followback1);
                        $fol1->execute([$usersession['id'],$getid]);
                        $followeach1 = $fol1->fetch();
                        //
                        $followback2 = "SELECT COUNT(*) FROM abonne WHERE idabonne = ? AND idabonnement = ?";
                        $fol2 = $db->prepare($followback2);
                        $fol2->execute([
                        $getid,$usersession['id']
                        ]);
                        $followeach2 = $fol2->fetch();
                        // COMPTAGE LIKE
                        // NOMBRE ABONNE
                      }
                      //idabo	idabonnement	idabonne	dateabonnement
                      ?>



                        <?php if($user) { ?>
                           <div class="media profil">
                               <?php echo '<img src=" uploads/' . $user['image'] . ' " id=avatar width="80" height="80" alt="Profil" class="align-self-start mr-3">';
                               if(isset($_SESSION['email']) AND !empty($_SESSION['email'])){
                                   if($followeach1[0]==1){
                                   echo '<img src="voussuit.png"  width="200" height="100">' ;
                                   }
                               } ?>
                               <div class="media-body">
                                 <h5 class="mt-0"><?php echo ' '  . $user['pseudo']. '';?></h5>
                                 <p>
                                   <?php if(isset($_SESSION['email']) AND $_SESSION['email']!=$user['email'] ){
                                   echo '<a href="messagerieavec.php?id='.$user['id'].'">Message</a>';
                                 }else{

                                 }?>
                                 </p>

                                 <p>
                                   <?php echo ' <a href="gensabonne.php?id='.$user['id'].'">Abonné(s)</a> : ' . $numberabo[0]. ' || ' ;?>
                                   <?php echo ' <a href="gensabonnement.php?id='.$user['id'].'"> Abonnement(s)</a> : ' . $numberabon[0]. '' ;?>
                                 </p>
                                 <p>
                                   <?php echo ' Bio : ' . $user['bio'] . '</p> '; ?>
                                 </p>
                                 <p>
                                   <?php if (!isset($_SESSION['email']) || empty($_SESSION['email'])|| $getid==$usersession['id']){

                                       }else{

                                       if($selecttoutabonne[0]==0){
                                         echo '<form method="POST"><input method="POST" name ="follow" type="submit" value="follow" /></form>';

                                         }else{
                                             echo '<form method="POST"><input name ="unfollow" type="submit" value="unfollow" /></form>';
                                               }
                                   } ?>
                                 </p>

                                <?php $toutpublication = "SELECT * FROM publication WHERE idauteur = ?";
                                 $reqos = $db->prepare($toutpublication);
                                 $reqos->execute([
                                 $user['id']

                                 ]);
                                 if (isset($_GET['supprimer'])) {
                                   $postsupprime=$_GET['supprimer'];
                                 $supprime = "DELETE FROM publication WHERE idpublication = ? AND idauteur = ?";
                                 $requete = $db->prepare($supprime);
                               $requete->execute([
                                 $postsupprime,$user['id']
                               ]);
                               header('Location:monprofil.php');

                             }?>






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
                                   <p>

                                   </p>
                                   <?php
                                   if(isset($_SESSION['email']) AND !empty($_SESSION['email'])){
                                     if($usersession['id']!=$_GET['id']){}else{ ?>
                                   <a href="monprofil.php?supprimer=<?php echo $p['idpublication'];?>">Supprimer</a>
                                 <?php }} ?>
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
