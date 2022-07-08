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
?>
        <div class="track">

         <?php echo '<h2>Vous trackez :' .$user['pseudo']. '</h2>' ?>
           <a href="trackercommentaire.php?id=<?=$user['id']; ?>">COMMENTAIRE</a><br>
              <a href="trackerpublication.php?id=<?=$user['id']; ?>">PUBLICATION</a><br>
                 <a href="trackermessage.php?id=<?=$user['id']; ?>">MESSAGE</a>

          </div>

          </main>
  <?php include('includes/footer.php'); ?>
  </body>
</html>
