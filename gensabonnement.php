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


                                      //

                                      //
                                      $getidsql = "SELECT * FROM users WHERE id =$getid";
                                      $reqaa = $db->prepare($getidsql);
                                      $reqaa->execute();
                                      $getinfo = $reqaa->fetch();
                                      // TOUS ABONNE
                                      $getidsql = "SELECT * FROM abonne INNER JOIN users WHERE id = idabonne AND $getid = idabonnement ";
                                      $reqaa = $db->prepare($getidsql);
                                      $reqaa->execute();
                                      $getinfo = $reqaa->fetchAll();
                                      // SELECT * FROM message WHERE iddestinataire = 1 AND idexpediteur = 4 OR iddestinataire = 4 AND idexpediteur = 1

                                      // zeaz

                                      ?>
                  <main>
                    <div class="track1">
                    <button ><a type="button" href="profil.php?id=<?php echo $getid;?>">retour</a></button><br><br>




                    <?php
                    foreach ($getinfo as $abo ) {
                      echo '<img src=" uploads/' . $abo['image'] . ' " id=avatar width="50" height="50" alt="Profil">' ;
                     echo '<a href="profil.php?id=' .$abo['id'].' "> '. $abo['pseudo'] .' </a>';


                    ?>



                  <?php }
                  ?>
</div>



                     </main>

    <?php include('includes/footer.php');?>
  </body>
<html>
