<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-107287293-1"></script>



  <script>

  setTimeout(function() {
    if ( window.location == 'http://www.backdrum.net/ad/banner3.php' ) {
      window.location.href='https://play.google.com/store/apps/details?id=com.orakinc.hellocookingdays';
    }
  }, 10);

  </script>

</head>
<body>

  <?php
  $myfile = fopen("banner3.txt", "r") or die("Unable to open file!");
  $count = fread($myfile, 5);
  $count = $count + 1;
  fclose($myfile);

  $openfile = fopen("banner3.txt", "w") or die("Unable to open file!");
  fwrite($openfile, $count);
  fclose($openfile);
  ?>


</body>
</html>
