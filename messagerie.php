<?php
  error_reporting(-1);
  ini_set('display_errors', 'On');
   ?>

<html>
<head>
  <?php include('includes/head.php');?>
</head>
<body>



<?php
    include('includes/header.php');
    ?>
    <?php



       if(isset($_SESSION['email'])){

       }else{
      header('location: index.php?');
}
$bg = "SELECT * FROM users ";
                $reqz = $db->prepare($bg);
                $reqz->execute();
                $idboug = $reqz->fetchAll();

  $monemail=$_SESSION['email'];
  $q = "SELECT * FROM users WHERE email ='$monemail'";
              $reqos = $db->prepare($q);
              $reqos->execute([]);
$userInfos = $reqos->fetch();


if(isset($_POST['message'])&&isset($_POST['destinataire'])){

    $contenu=$_POST['message'];
    $destinataire=$_POST['destinataire'];










                      $caca = "INSERT INTO message (datemessage, content, idexpediteur,iddestinataire) VALUES ( NOW(),?,?,? )";
                      $envoyemessage =$db->prepare($caca);
                      $envoyemessage->execute([$contenu,$userInfos['id'],$rechercheidparpseudo['id']]);



}


$qbq = "SELECT distinct pseudo,id FROM message INNER JOIN users WHERE  idexpediteur = ? AND iddestinataire= id || iddestinataire = ? AND idexpediteur= id";
$reqq = $db->prepare($qbq);
$reqq->execute([$userInfos['id'],$userInfos['id']]);
$Touslesboug = $reqq->fetchAll();




       ?>
  <main>

    <div class="recherche1">

    <?php
    foreach ($Touslesboug as $bou ) {
      ?>
      <div class="recherche">
      <p>______________________________</p>
      <a href="messagerieavec.php?id=<?php echo $bou['id']; ?>"><?= $bou['pseudo'] ;?></a></div>
      <?php
    }


?>
    </div>


<div>

 </div>
</main>
<?php include('includes/footer.php');?>
</body>
