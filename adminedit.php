<!DOCTYPE html>
<?php
  session_start();
    include('includes/db.php');
        $users = $db->query('SELECT * FROM users');
        $clientID;
        $userCount;
        $userInfo;
        $monemail=$_SESSION['email'];
        $sa = "SELECT * FROM users WHERE email ='$monemail'";
                    $reqzo = $db->prepare($sa);
                      $reqzo->execute([]);
      $usersession =  $reqzo->fetch();


        if (isset($_GET['id'])) {
          $clientID = $_GET['id'];
          $requestUser = $db->prepare("SELECT * FROM users WHERE id = ?");
          $requestUser->execute(array($clientID));
          $userCount = $requestUser->rowCount();
          $userInfo = $requestUser->fetch();
        }
        if (isset($_POST['delete'])) {
          $request = $db->prepare('DELETE FROM users WHERE email = ?');
          $request->execute([
          $userInfo['email'],
          ]);
          header('Location:adminedit.php');
        }
        if(isset($_SESSION['email']) AND $usersession['role']=='admin'){

        }else{
          header('location:hack.mp4');
        }

        if (isset($_POST['edit'])) {
          $userEmail = $userInfo['email'];
          $userPseudo = $userInfo['pseudo'];
          $emailValue = htmlspecialchars($_POST['emailedit']);
          $pseudoValue = htmlspecialchars($_POST['pseudoedit']);
          $userRole = $userInfo['role'];
          $roleValue = htmlspecialchars($_POST['roleedit']);

          if ($userEmail != $emailValue) {
            $rowEmail = countDatabaseValue($db, 'email', $emailValue);
            if ($rowEmail == 0) {
              $request = $db->prepare("UPDATE users SET email = ? WHERE email = ?");
              $request->execute([
              $emailValue,
              $userEmail
              ]);
              $succesMessage = 'Les informations ont bien été modifiés!';
            } else {
              $errorMessage = 'Cette adresse email existe déjà !';
            }
            header('refresh:0;url=adminedit.php');

          }

          if ($userPseudo != $pseudoValue) {
            $rowPseudo = countDatabaseValue($db, 'pseudo', $pseudoValue);
            if ($rowPseudo == 0) {
              $request = $db->prepare("UPDATE users SET pseudo = ? WHERE pseudo = ?");
              $request->execute([
              $pseudoValue,
              $userPseudo
              ]);
              $succesMessage = 'Les informations ont bien été modifiés!';
            } else {
              $errorMessage = 'Ce pseudo existe déjà !';
            }
            header('refresh:10;url=adminedit.php');

          }
          if (isset($_POST['roleedit']) AND $userRole != $roleValue) {
            $roleValue = htmlspecialchars($_POST['roleedit']);
          $rowRole = countDatabaseValue($db, 'role', $RoleValue);
          if ($rowRole == 0) {
          $request = $db->prepare("UPDATE users SET role = ? WHERE pseudo = ?");
          $request->execute([
          $roleValue,
          $userPseudo
          ]);
          $succesMessage = 'Les informations ont bien été modifiés!';
          } else {
          $errorMessage = 'Cette adresse email existe déjà !';
          }
          header('refresh:10;url=adminedit.php');

          }
        }

        if ((isset($_POST['ban'])) || isset($_POST['unban'])) {
          $banValue = 0;
          if ($userInfo['isban'] == 0) {
            $banValue = 1;
          }
          $request = $db->prepare("UPDATE users SET isban = ? WHERE email = ?");
          $request->execute([
          $banValue,
          $userInfo['email']
          ]);
          $succesMessage = "Vous venez de " . ($banValue == 0 ? 'Débannir' : 'Bannir') . ' le client!';
          header('refresh:1;url=adminedit.php');
        }
        ?>

<html>
  <head>
    <?php include('includes/head.php'); ?>
  </head>
  <body>
  <?php include('includes/header2.php'); ?>
      <main>


        <div class="adminedit">


          <div class="adminedit1">
            <?php
            $visite=file_get_contents('counter.txt');
            echo '<h1>Nombre de visites : '.$visite.'.</h1>';
            ?>
          </div>


          <?php include('includes/message.php'); ?>
          <?php
          // vérifier si il posse admin
          if(isset($_SESSION['pseudo'])){
          }else{
          header('location: admin.php?');
          }
          if(isset($_POST['recherchepseudo'])){
          $mot = htmlentities($_POST['recherchepseudo'], ENT_QUOTES, 'UTF-8');
          $req = $db->prepare("SELECT * FROM users WHERE pseudo LIKE '%$mot%'");
          $req->execute();
          $users = $req->fetchAll();
          }else{
          $req = $db->prepare("SELECT * FROM users WHERE pseudo LIKE '%%'");
          $req->execute();
          $users = $req->fetchAll();
        }?>

      <div class="adminedit2">
        <form method="post" action="">
            <div class="form-group">
              <label>Pseudo à rechercher :</label>
              <input type="text" name="recherchepseudo" placeholder="recherche par pseudo" value="" class="form-control">
            <button type="submit" class="btn btn-primary" href="adminedit.php">Nouvelle recherche ?</button>


          </form>
        </div>
      </div>

          <div class="adminedit3">
                <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">email</th>
                          <th scope="col">pseudo</th>
                          <th scope="col">ip</th>
                          <th scope="col">role</th>
                          <th scope="col">avatar</th>
                          <th scope="col">date d'inscription</th>
                          <th scope="col">date derniere visite</th>
                          <th scope="col">Confirmé</th>
                          <th scope="col">action</th>
                          <th scope="col">Track</th>
                        </tr>
                      </thead>
                      <tbody>

                        <?php foreach ($users as $u){
                          ?>
                        <tr>
                        <td><?php echo $u['email']; ?> </td>
                        <td> <?php echo $u['pseudo']; ?> </td>
                        <td><?php echo $u['ip']; ?> </td>
                        <td> <?php echo $u['role']; ?> </td>
                          <td> <a href="profil.php?id=<?php echo $u['id']; ?>"> <img src="uploads/<?= $u['image'] ?>" width="50" height="50" alt="Pas d'image"></a></td>
                        <td><?php echo $u['dateregister']; ?> </td>
                          <td><?php echo $u['datelastvisite']; ?> </td>
                          <td><?php echo $u['confirme']?"Oui":"Non"; ?> </td>
                        <td><a href="adminedit.php?id=<?php echo $u['id']; ?>#adminus">Gérer</a></td>
                          <td><a href="tracker.php?id=<?php echo $u['id']; ?>">Tracker</a></td>
                      </tr>
                    <?php }?>
                      </tbody>
                    </table>
                  </div>







      <div class="adminedit4">


          <?php
          if (isset($clientID)) {
          if ($userInfo == null) { echo "Ce compte n'existe pas !"; } else { ?>
          <form  id='adminus' method="post" action="">
            <h3>Gérer : <?= $userInfo['pseudo'] ?></h3>
            <?php if (isset($errorMessage)) { ?> <p style="color: red;"><?= $errorMessage ?></p> <?php } ?>
            <?php if (isset($succesMessage)) { ?> <p style="color: green;"><?= $succesMessage ?></p> <?php } ?>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="emailedit" placeholder="Email" value="<?= $userInfo['email'] ?>">
                </div>
              <div class="form-group">
                <label>Pseudo</label>
                <input type="text" class="form-control" name="pseudoedit" placeholder="Pseudo" value="<?= $userInfo['pseudo'] ?>">
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="roleedit" value="admin" >
                <label class="form-check-label">Admin</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="roleedit" value="membre">
                <label class="form-check-label" for="exampleRadios1">Membre</label>
              </div>
              <button type="submit" name="edit" value="Editer" class="btn btn-primary">Editer</button>
              <?php if($userInfo['isban'] == 0) {?>
              <button type="submit" name="ban" value="Bannir" class="btn btn-primary">Bannir</button>
              <?php } else { ?>
              <button type="submit" name="unban" value="Déban" class="btn btn-primary">Déban</button>
              <?php } ?>
              <button type="submit" name="delete" value="Supprimer le Compte" class="btn btn-primary">Supprimer le compte</button>
            </form>
          <?php }
          }
          ?>


        </div>

      </div>


  </main>
  <?php include('includes/footer.php'); ?>
  </body>
</html>
