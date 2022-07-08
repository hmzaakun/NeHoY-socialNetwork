<?php
session_start();


include("includes/db.php");

if(isset($_GET['user'])){
  $user = $_GET['user'];

}



$recherche="SELECT * FROM users WHERE pseudo LIKE  '%$user%'";
$req = $db->prepare($recherche);
$req->execute();
$u= $req->fetch();

if($u){

  echo '<img src=" uploads/' . $u['image'] . ' " id=avatar width="50" height="50" alt="Profil">' ;
 echo '<a href="profil.php?id=' .$u['id'].' "> '. $u['pseudo'] .' </a>';





}



?>
