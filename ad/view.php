<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-107287293-1"></script>


  <!--
  <script>

  setTimeout(function() {
  if ( window.location == 'http://www.backdrum.net/show.php' ) {
  window.location.href='https://play.google.com/store/apps/details?id=com.thepofeskifactory.bubblefriends';
}
}, 10);

</script> -->

</head>
<body>
  <div>
    <h3><strong>banner1</strong></h3>
  </div>
  <div class="banner">
    <?php
    $banner1 = fopen("banner1.txt", "r") or die("Unable to open file!");
    $content = fread($banner1, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>banner2</strong></h3>
  </div>
  <div class="banner">
    <?php
    $banner2 = fopen("banner2.txt", "r") or die("Unable to open file!");
    $content = fread($banner2, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>banner3</strong></h3>
  </div>
  <div class="banner">
    <?php
    $banner3 = fopen("banner3.txt", "r") or die("Unable to open file!");
    $content = fread($banner3, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>banner4</strong></h3>
  </div>
  <div class="banner">
    <?php
    $banner4 = fopen("banner4.txt", "r") or die("Unable to open file!");
    $content = fread($banner4, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>face1</strong></h3>
  </div>
  <div class="banner">
    <?php
    $face1 = fopen("face1.txt", "r") or die("Unable to open file!");
    $content = fread($face1, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>face2</strong></h3>
  </div>
  <div class="banner">
    <?php
    $face2 = fopen("face2.txt", "r") or die("Unable to open file!");
    $content = fread($face2, 5);
    echo $content;
    ?>
  </div>

  <div>
    <h3><strong>face3</strong></h3>
  </div>
  <div class="banner">
    <?php
    $face3 = fopen("face3.txt", "r") or die("Unable to open file!");
    $content = fread($face3, 5);
    echo $content;
    ?>
  </div>



</body>
</html>
