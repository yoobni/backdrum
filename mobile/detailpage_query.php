
<?php
#비정상적인 접근 방지

if(is_null($_GET['id'])){
  echo "<script>alert('삭제되거나 없는 게시물 입니다.');history.back()</script>";
}

#데이터 가져오기

$data_query=mysqli_query($connect, "SELECT * FROM `board` WHERE `idx` = '$_GET[id]'");
$next=$_GET[id]+1;
$next_query=mysqli_query($connect, "SELECT * FROM `board` WHERE `idx` = '$next'");
$ago=$_GET[id]-1;
$ago_query=mysqli_query($connect, "SELECT * FROM `board` WHERE `idx` = '$ago'");

$data= mysqli_fetch_array($data_query);
$next_data=mysqli_fetch_array($next_query);
$ago_data=mysqli_fetch_array($ago_query);
if(is_null($data[1])){
  echo "<script>alert('삭제되거나 없는 게시물 입니다.');history.back()</script>";
}

#Youtube 창 크기 데이터베이스에 아예 정해져 있길래 문자열  치환으로 처리
$data[content]= str_replace('width="560"', " ", "$data[content]");
$data[content]= str_replace('height="315"', " ", "$data[content]");

$data[content]= str_replace("img", "img width='100%'", "$data[content]");

?>