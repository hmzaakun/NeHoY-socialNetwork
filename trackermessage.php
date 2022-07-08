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

    $touslesmsg = "SELECT * FROM message INNER JOIN users WHERE id=iddestinataire AND idexpediteur = ? ORDER BY datemessage ASC ";
    $msgg = $db->prepare($touslesmsg);
    $msgg->execute([
   $getid
    ]);
    $touslesmsg = $msgg->fetchAll();
    if (isset($_GET['supprimemess']) AND !empty($_GET['supprimemess']) AND isset($_SESSION['email']) AND !empty($_SESSION['email']) ) {
      $idmess=$_GET['supprimemess'];
    $supprimemess = "DELETE FROM message WHERE idmessage = $idmess ";
    $suppressage = $db->prepare($supprimemess);
  $deletemessage=$suppressage->execute();
  header('location:trackermessage.php?id='.$user['id']);
} ?>

  <div class="track1">
        <button><a type="button" href="tracker.php?id=<?php echo $getid;?>">retour</a></button>

      <div class="adminedit3">
                      <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">expéditeur</th>
                                <th scope="col">destinataire</th>
                                <th scope="col">message</th>
                                <th scope="col">date</th>
                                <th scope="col">gérer</th>
                              </tr>
                            </thead>
                            <tbody>

                              <?php foreach ($touslesmsg as $msg ){
                                ?>
                              <tr>
                              <td><?php echo $user['pseudo']; ?> </td>
                              <td><?php echo $msg['pseudo']; ?> </td>
                              <td><?php echo $msg['content']; ?> </td>
                              <td><?php echo strftime("%A %d %B %G %H:%M", strtotime($msg['datemessage']));?> </td>
                                <td><a href="trackermessage.php?id=<?php echo  $user['id'];?>&supprimemess=<?php echo  $msg['idmessage'];?>">Supprimer</a> </td>
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
