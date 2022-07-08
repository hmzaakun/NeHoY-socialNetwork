<?php
  error_reporting(-1);
  ini_set('display_errors', 'On');
  session_start();
  include('includes/db.php');
  // on prend les infos de la session
  if (isset($_SESSION['email']) AND !empty($_SESSION['email'])){
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
        /*if (!isset($_GET['id']) AND empty($_GET['id'])){
        header('Location:recherche.php'); // publication.php?publication=<?php echo $publication['idpublication'];?>
        }
        */

        if(isset($_GET['publication'])){
            $getpublication=$_GET['publication'];

          //

        }else{

        }

        $publi = "SELECT * FROM publication INNER JOIN users WHERE idpublication = ? AND idauteur=id";
        $requette = $db->prepare($publi);
        $requette->execute([
        $getpublication
        ]);
        $publication = $requette->fetch();
        if(isset($_GET['like']) AND isset($_SESSION['email']) ){

          // 	idlike	liketime	idliker	publicationid
          // INSERT INTO `likepublication` (idliker,idpublication,date) VALUES (1,13,NOW());
          $preparelike="INSERT INTO liked (idliker,publicationid,liketime) VALUES (?,?,NOW())";
          $reqaz = $db->prepare($preparelike);
          $like=$reqaz->execute([
            $usersession['id'],$_GET['like']
          ]);
          header('location:publication.php?publication='.$publication['idpublication']);
        }
        if(isset($_GET['dislike']) AND isset($_SESSION['email']) ){

          // 	idlike	liketime	idliker	publicationid
          // INSERT INTO `likepublication` (idliker,idpublication,date) VALUES (1,13,NOW());
          $preparedislike="DELETE FROM liked WHERE idliker = ? AND publicationid = ?";
          $reqaz = $db->prepare($preparedislike);
          $dislike=$reqaz->execute([
            $usersession['id'],$_GET['dislike']
        ]);
          header('location:publication.php?publication='.$publication['idpublication']);

      }
      if (isset($_GET['idcom']) AND !empty($_GET['idcom']) AND isset($_SESSION['email']) AND !empty($_SESSION['email']) ) {
        $idcom=$_GET['idcom'];
      $supprimecom = "DELETE FROM commentaire WHERE idcommentaire = $idcom  ";
      $supprcom = $db->prepare($supprimecom);
    $deletelike=$supprcom->execute();
    header('location:publication.php?publication='.$publication['idpublication']);
        }
// COMPTEUR LIKE
        $preparecountlike = "SELECT COUNT(*) FROM liked WHERE publicationid = ?";
        $countlike = $db->prepare($preparecountlike);
        $countlike->execute([
        $getpublication
        ]);
        $compteurlike = $countlike->fetch();
        // VERIFICATION LIKE PAS BZEF
        if(isset($_SESSION['email']) AND !empty($_SESSION['email'])) {
        $veriflike = "SELECT COUNT(*) FROM liked WHERE publicationid = ? AND idliker = ?";
        $likeverif = $db->prepare($veriflike);
      $likeverif->execute([
        $getpublication,$usersession['id']
        ]);
        $verificationlike = $likeverif->fetch();

        if(isset($_POST['contenucom']) AND !empty($_POST['contenucom'])){
          $contenucom= htmlspecialchars($_POST['contenucom']);

          $preparelike="INSERT INTO commentaire (idauteurcom,idpublication,contenucom) VALUES (?,?,?)";
          $reqaz = $db->prepare($preparelike);
          $like=$reqaz->execute([
            $usersession['id'],$getpublication,$contenucom
          ]);
          header('location:publication.php?publication='.$getpublication.'');
        }
      }

      $affichecomentaire = "SELECT * FROM commentaire INNER JOIN users WHERE idpublication = ? AND idauteurcom=id";
      $commentairedb = $db->prepare($affichecomentaire);
  $commentairedb->execute([
      $getpublication
      ]);
      $commentaire = $commentairedb->fetchAll();


        ?>
        <div class="publi">
        <?php   if($publication){
        ?>
        <div class="publi1">
        <?php echo '<img src="publicationimage/' . $publication['imagepublication']. ' "  width="" height="" alt="publication">' ; ?>
        <p>
          <h3><?php echo'<a href="profil.php?id=' .$publication['id'].' "> '. $publication['pseudo'] .' </a>' ?></h3>
        </p>
        <p>
          <h6><?php echo '<a href="https://www.google.fr/maps/place/' .$publication['localisation'].' " target="_blank"> <h4>'. $publication['localisation'] .' </h4> </a>'; ?></h6>
        </p>
        <p>
        <?php echo $publication['descriptionpublication']; ?>
        </p>
        <p>
        <?php echo  strftime("%A %d %B %G %H:%M", strtotime($publication['datepublication']));  ?>

        </p>
        <p><?php echo 'LIKE :'.$compteurlike[0]; ?></p>
        <p>
        <?php if (!isset($_SESSION['email']) || empty($_SESSION['email'])|| $publication['idauteur']==$usersession['id']){}else{
          if ($verificationlike[0]==0){
        ?>
        <a href="publication.php?publication=<?php echo $publication['idpublication'];?>&like=<?php echo $publication['idpublication'];?>">J'aime</a> <?php
      }else{


        ?> <a href="publication.php?publication=<?php echo $publication['idpublication'];?>&dislike=<?php echo $publication['idpublication'];?>">J'aime plus</a>
        <?php
      }



        }
        ?>
        </p>
        </div>
        <?php  ?>

        <?php } else {
        echo '<center><h2>PUBLICATION  introuvable !</h2></center>';
      } ?>
            <div class="comm">
              <div class="comm1">
            <?php  foreach ($commentaire as $com) { ?>

              <?php
                if(isset($_SESSION['email']) AND !empty($_SESSION['email'])){
                  echo $com['pseudo']==$usersession['pseudo']||$usersession['role']=='admin'?$com['pseudo'].'<a href="publication.php?publication='.$getpublication.'&idcom='.$com['idcommentaire'].'"> Supprimer </a>':$com['pseudo'];
                }else{
                    echo $com['pseudo'];
                }

                echo ' : ';
                echo $com['contenucom'];
                echo '<br>---------------------<br>'; ?>


            <?php  }
              ?></div>
   <?php if(isset($_SESSION['email']) AND !empty($_SESSION['email'])) { ?>
              <form method="POST" class="" >
                	<input type="text" name="contenucom" placeholder="Commentaire">
                   <input type="submit" value="Envoyer le commentaire" >
                </form>
              <?php } ?>
                </div>
            </div>

    </main>
    <?php include('includes/footer.php'); ?>
  </body>
</html>
