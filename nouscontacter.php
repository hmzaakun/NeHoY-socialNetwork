<!DOCTYPE html>
<?php

  error_reporting(-1);
  ini_set('display_errors', 'On');

  require "PHPMailer/PHPMailerAutoload.php";


if(isset($_POST['nom'])AND !empty($_POST['nom'])){
  function smtpmailer($to, $from, $from_name, $subject, $body)
      {
          $mail = new PHPMailer();
          $mail->IsSMTP();
          $mail->SMTPAuth = true;

          $mail->SMTPSecure = 'ssl';
          $mail->Host = 'smtp.gmail.com';
          $mail->Port = 465;
          $mail->Username = 'nhorizony@gmail.com';
          $mail->Password = 'mailduprojet94';

     //   $path = 'reseller.pdf';
     //   $mail->AddAttachment($path);

          $mail->IsHTML(true);
          $mail->From='nhorizony@gmail.com';
          $mail->FromName=$from_name;
          $mail->Sender=$from;
          $mail->AddReplyTo($from, $from_name);
          $mail->Subject = $subject;
          $mail->Body = $body;
          $mail->AddAddress($to);
          if(!$mail->Send())
          {
              $error ="Erreur";
              return $error;
          }
          else
          {
              $error = "Erreur";
              return $error;
          }

      }
      $to   = 'nhorizony@gmail.com';
      $from = $_POST['mail'];
      $name = $_POST['nom'];
      $subj = ''.$_POST['mail']. '(' .$_POST['objet'].')';
      $msg = nl2br($_POST['message']);
      $error=smtpmailer($to,$from, $name ,$subj, $msg);
    }


?>

<html>
<head>
<?php include('includes/head.php');?>
</head>
<body>
<?php include('includes/header.php');?>
  <main>


            <div class="contact">

            				<form method="POST" name="myForm">
            				  <div class="form-group">
            						<h2>Nous Contacter</h2>

            				    <label>Nom :</label>
            				    <input name="nom" type="text" placeholder="Votre nom">
            				  </div>
            				  <div class="form-group">
            				    <label>email :</label>
            				    <input name="mail" type="text" placeholder="Votre mail">
            				  </div>
                      <div class="form-group">
                        <label>Objet :</label>
                        <textarea class="form-control text1" name="objet" type="text" rows="3" placeholder="Objet"></textarea>
                      </div>
                      <div class="form-group">
                        <label>Message :</label>
                        <textarea class="form-control text2" name="message" rows="5" placeholder="Bonjour..."></textarea>
                      </div>
            				  <button type="submit" class="btn btn-primary" value="smtpmailer()" name="mailform">Envoyer</button>
            				</form>
            </div>

                  <?php
                  if(isset($_POST['mailform']))
                  {
                		if(isset($msg))
                		{
                			echo '<center><h2>C\'EST ENVOYÉ !</h2></center>';
                		}else {
                      echo '<center><h2>VÉRIFIEZ LES CHAMPS</h2></center>';
                    }
                  }
                	?>
          </main>




          <?php include('includes/footer.php'); ?>
</body>
</html>
