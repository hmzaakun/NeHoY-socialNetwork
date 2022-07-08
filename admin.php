<!DOCTYPE html>
		<?php
			?>
	<html>
		<?php include('includes/head.php'); ?>
		<style media="screen">
		footer {
			margin-top: 140px;
		}
		</style>
			<body>
			<?php include('includes/header.php'); ?>

			<main>

				<div class="taille">


						<div class="connex">

										<form class="" action="verificationadmin.php" method="post">
												<h2>CONNEXION ADMIN</h2>
											<?php 	if(isset($_GET['message']) && !empty($_GET['message'])){?>
													<p class="error"><?php
													echo $_GET['message'];
													?></p><?php
												}
												?>
										    <label for="exampleInputEmail1"></label>
										     <input type="pseudo" name="pseudo" placeholder="Pseudo" value="<?= isset($_COOKIE['pseudo']) ? $_COOKIE['pseudo'] : '' ?>" required="required">
										    <small id="emailHelp" class="form-text text-muted">Ici c'est que pour les admins ᕦ(Ò_Óˇ)ᕤ</small>
										  <div class="form-group">
										    <label for="exampleInputPassword1"></label>
										    <input type="password" class="form-control" name="password" placeholder="mot de passe" required="required">
										  </div>
										  <button type="submit" class="btn btn-primary">Se connecter</button>
										</form>
						</div>
					</div>


			</main>

			<?php include('includes/footer.php'); ?>




				</body>
</html>
