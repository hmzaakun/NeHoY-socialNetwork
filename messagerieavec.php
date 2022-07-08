<?php
  error_reporting(-1);
  ini_set('display_errors', 'On');



?>
<html>
<head>
<?php include('includes/head.php'); ?>
</head>
  <body>

    <?php include('includes/header.php'); ?>

                                    <?php
                                      if(isset($_SESSION['email'])){}
                                      else{
                                        header('location: index.php?');
                                      }
                                      if(isset($_GET['id']) AND !empty(isset($_GET['id']))) {
                                      $getid=$_GET['id'];
                                    }else{
                                      header('Location:index.php');
                                    }

                                      $bg = "SELECT * FROM users";
                                      $reqz = $db->prepare($bg);
                                      $reqz->execute();
                                      $idboug = $reqz->fetchAll();
                                      //
                                      $monemail=$_SESSION['email'];
                                      //
                                      $q = "SELECT * FROM users WHERE email ='$monemail'";
                                      $reqos = $db->prepare($q);
                                      $reqos->execute([]);
                                      $userInfos = $reqos->fetch();
                                      //
                                      $getidsql = "SELECT * FROM users WHERE id =$getid";
                                      $reqaa = $db->prepare($getidsql);
                                      $reqaa->execute([]);
                                      $getinfo = $reqaa->fetch();
                                      // SELECT * FROM message WHERE iddestinataire = 1 AND idexpediteur = 4 OR iddestinataire = 4 AND idexpediteur = 1
                                      $touslesmsg = "SELECT * FROM message INNER JOIN users WHERE id=idexpediteur AND iddestinataire = ? AND idexpediteur = ? OR iddestinataire = ? AND idexpediteur = ? AND id=idexpediteur ORDER BY datemessage ASC ";
                                      $msgg = $db->prepare($touslesmsg);
                                      $msgg->execute([
                                     $userInfos['id'],$getid,$getid,$userInfos['id']
                                      ]);
                                      $touslesmsg = $msgg->fetchAll();
                                      // zeaz
                                      if(isset($_POST['message']) AND !empty($_POST['message']) ){
                                      $contenu=htmlspecialchars($_POST['message']);
                                      $envoiemessage = "INSERT INTO message (datemessage, content, idexpediteur,iddestinataire) VALUES ( NOW(),?,?,? )";
                                      $envoyemessage =$db->prepare($envoiemessage);
                                      $envoyemessage->execute([$contenu,$userInfos['id'],$getid]);
                                      header("Refresh:0");
                                      //
                                    }
                                      ?>
                  <main>
                    <div class="message">
                      <div class="message1" id="message1">



                    <?php
                    foreach ($touslesmsg as $msg ) {

                    ?>

                    <?php echo $msg['pseudo']==$userInfos['pseudo']?'<div class="message2" id="own"><b>'.$msg['pseudo'].'</b><br>':'<div class="message2"><b>' .$msg['pseudo']. '</b><br>' ; ?>  <?php echo $msg['content']. '<br>'; ?>   <?php echo $msg['datemessage']; ?> </div>

                  <?php }
                  ?>


                  <style>
                      #own{
                        background: #63cdda;
                        text-align: end;
                        color: #596275;
                      }
                  </style>

                  </div>


                  <form id='messagedirect' method="POST" enctype="multipart/form-data">
                  <input type="text" maxlength="142" name="message" placeholder="message"/>
                  <input type="submit" value="Envoyer le message" />

                  </div>
                  </main>

<script>
var objDiv = document.getElementById("message1");
objDiv.scrollTop = objDiv.scrollHeight;
</script>
    <?php include('includes/footer.php');?>
  </body>
<html>
