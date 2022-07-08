<!DOCTYPE html>
<html lang="fr" dir="ltr">

  <head>
    <?php include('includes/head.php'); ?>
  </head>
  <?php include('includes/header.php');?>

          <body>
<div class="inscrip">


            <form action="verification_inscription.php" method="post" enctype="multipart/form-data">
                  <h2>INSCRIPTION</h2>
                     <?php
                    if(isset($_GET['message']) && !empty($_GET['message'])){?>
                      <p class="error"><?php
                      echo ($_GET['message']);
                    }
                    ?></p>


              <label for="email" >E-mail :</label>
               <input type="email" name="email" placeholder="Votre email" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>" required="required">

                  <label for="password" >Mot de passe : </label>
                  <input type="password" name="password"  pattern=".{5,}"
                                      title="Doit contenir au moins 5 caractères"
                                      placeholder="Mot de passe" required>



                  <label for="password" >Confirmation Mot de passe : </label>
                    <input type="password" name="password2" placeholder="Confirmation Mot de passe" required="required">

                <label for="text" >Pseudo :</label>
                <input type="text" name="pseudo" placeholder="Votre Nom d'utilisateur" required="required">



                <label for="text" >Nom :</label>
                <input type="text" name="nom" placeholder="Votre Nom" required="required">


                 <label for="text" >Prenom :</label>
                <input type="text" name="prenom" placeholder="Votre prenom" required="required">

               <label for="date" >Date de naissance :</label>
                <input type="date" name="datedenaissance" placeholder="Date de naissance" required="required" >

                <br><br>
                <label for="text" >Photo de profil :</label>
                <br><br>
                <input type="file" name="image" accept="image/jpeg,image/gif,image/png,image/jpg">
                  <br><br>
                  <label for="text" >Captcha :</label>
                  <br>
                  <img src="captcha.php" >
                  <input type="text" name="captcha" required="required" >



                 <button type="submit">S'INSCRIRE</button>
                 <a href="connexion.php" class="ca">Tu as déjà un compte?</a>

             </form>
</div>

<?php include('includes/footer.php');?>
        </body>


</html>
