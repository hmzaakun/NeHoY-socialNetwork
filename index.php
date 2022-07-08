<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php
    $ip = fopen('last_ip.txt', 'c+');
    $check = fgets($ip);
    $file = fopen('counter.txt', 'c+');
    $count = intval(fgets($file));
    if($_SERVER['REMOTE_ADDR'] != $check)
    {
    fclose($ip);
    $ip = fopen('last_ip.txt', 'w+');
    fputs($ip, $_SERVER['REMOTE_ADDR']);
    $count++;
    fseek($file, 0);
    fputs($file, $count);
    }
    fclose($file);
    fclose($ip);
    ?>
    <?php include('includes/head.php'); ?>

  </head>
  <body>
    <?php
    ini_set("display_errors", 1);

 include('includes/header.php');?>
    <main>
      <div class="taille">

      <div class="index">
        <h2>Un nouveau regard sur le monde</h2>
            <h4>Découvrir le monde, les plus beaux paysages de notre univers.<br>
                A travers les yeux de chacun, car nous avons tous une vision
                du monde qui nous est propre.<br> Et quoi de plus beau que de le partager au monde entier ?
            </h4>
      </div>
      <div class="index">
        <h2>Un projet dingue</h2>
            <h4>
              Des milliers de photographies aussi extraordinaires les unes que les autres concentrées en un seul site.
               Derrière, des histoires uniques, des vies parfois complètement opposées, mais liées par une même passion.
                Rejoins la communauté, et partons ensemble à la recherche de nouveaux horizons !
            </h4>
      </div>
    </div>
    </main>

    <?php include('includes/footer.php');?>
  </body>
</html>
