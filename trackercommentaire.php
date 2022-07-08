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

    ?>

<html>
  <head>
    <?php include('includes/head.php'); ?>
  </head>
  <body>
    <?php include('includes/header2.php'); ?>
    <main>

          <?php
          if(isset($_GET['id'])){
          $getid=$_GET['id'];
        }else{
          header('location:adminedit.php?');
        }
            $q = "SELECT * FROM users WHERE id = ?";
            $req = $db->prepare($q);
            $req->execute([
            $getid

            ]);
            $user = $req->fetch();

          $msgdumec = "SELECT * FROM commentaire INNER JOIN users WHERE id=idauteurcom AND id=? ORDER BY datecommentaire ASC ";
          $msgmek = $db->prepare($msgdumec);
          $msgmek->execute([
         $getid
          ]);
          $allcommentaireautor = $msgmek->fetchAll();
          if (isset($_GET['supprimecom']) AND !empty($_GET['supprimecom']) AND isset($_SESSION['email']) AND !empty($_SESSION['email']) ) {
            $idcom=$_GET['supprimecom'];
          $supprimecom = "DELETE FROM commentaire WHERE idcommentaire = $idcom ";
          $suppressage = $db->prepare($supprimecom);
        $deletemessage=$suppressage->execute();
        header('location:trackercommentaire.php?id='.$user['id']);
      } ?>
      <div class="track1">
        <button ><a type="button" href="tracker.php?id=<?php echo $getid;?>">retour</a></button>
            <div class="adminedit3">
                  <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">utilisateur</th>
                            <th scope="col">commentaire</th>
                            <th scope="col">date</th>
                            <th scope="col">gérer</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php foreach ($allcommentaireautor as $commentaire ){
                            ?>
                          <tr>
                          <td><?php echo $user['pseudo']; ?> </td>
                          <td><?php echo $commentaire['contenucom']; ?> </td>
                          <td><?php echo strftime("%A %d %B %G %H:%M", strtotime($commentaire['datecommentaire'])); ?> </td>
                          <td><a href="trackercommentaire.php?id=<?php echo  $user['id'];?>&supprimecom=<?php echo  $commentaire['idcommentaire'];?>">Supprimer</a> </td>
                        </tr>
                      <?php }?>
                        </tbody>
                      </table>
                    </div>
                </div>

      </main>

  <?php include('includes/footer.php'); ?>
  </body>
</html>
