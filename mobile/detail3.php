<?php
require_once ("connect.php");
require_once ("mainpage_query.php");
?>	
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

$data[content]= str_replace('src"', " ", "$data[content]");
?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">

		<link rel="stylesheet" type="text/css" href="css/detail.css">

		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>

	<body>
		<nav class="nav-extended white black-text">
			<div class="nav-wrapper ">
				<a href="#" class="brand-logo"><img src="http://jjaltoon.backdrum.net/images/logo.png" width="80px"></a>
				<a href="#" data-activates="mobile-demo " class="button-collapse"><i class="material-icons grey-text">menu</i></a>
				<ul id="nav-mobile" class="right hide-on-med-and-down ">
					<li><a href="sass.html" class="black-text">Sass</a></li>
					<li><a href="badges.html">Components</a></li>
					<li><a href="collapsible.html">JavaScript</a></li>
				</ul>
				<ul class="side-nav" id="mobile-demo">

					<li class="blue white-text">실시간 인기 뒷북</li>
					<li><a href="badges.html"><span class="ingi-num blue-text">1</span> 가나다 라마바사 아자차</a></li>
					<li><a href="collapsible.html"><span class=" ingi-num blue-text">2</span>  사쿠라 유이 파해치기</a></li>
					<li><a href="badges.html"><span class="ingi-num blue-text">3</span> 가나다 라마바사 아자차</a></li>
					<li><a href="collapsible.html"><span class=" ingi-num blue-text">4</span>  사쿠라 유이 파해치기</a></li>
					<li><a href="badges.html"><span class="ingi-num blue-text">5</span> 가나다 라마바사 아자차</a></li>
					<li><a href="collapsible.html"><span class=" ingi-num blue-text">6</span>  사쿠라 유이 파해치기</a></li>
					<li><a href="badges.html"><span class="ingi-num blue-text">7</span> 가나다 라마바사 아자차</a></li>
					<li><a href="collapsible.html"><span class=" ingi-num blue-text">8</span>  사쿠라 유이 파해치기</a></li>
					<li><a href="badges.html"><span class="ingi-num blue-text">9</span> 가나다 라마바사 아자차</a></li>
					<li><a href="collapsible.html"><span class=" ingi-num blue-text">10</span>  사쿠라 유이 파해치기</a></li>
				</ul>
			</div>
			
			<div class="nav-content">
				<ul class="tabs tabs-transparent blue">
					<li class="tab"><a  href="#test1">전체기사</a></li>
					<li class="tab"><a href="#test2">사회이슈</a></li>
					<li class="tab"><a href="#test3">재밌는거</a></li>
					<li class="tab"><a href="#test4">꿀팁정보</a></li>
					<li class="tab"><a href="#test4">연예계 썰</a></li>
					<li class="tab"><a href="#test4">리뷰&후기</a></li>
					<li class="tab"><a href="#test4">사건사고</a></li>
					<li class="tab"><a href="#test4">애완동물</a></li>
				</ul>
			</div>
		</nav>
		<ul id="slide-out" class="side-nav">
			<li><div class="user-view">
				<div class="background">
					<img src="images/office.jpg">
				</div>
				<a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
				<a href="#!name"><span class="white-text name">John Doe</span></a>
				<a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
			</div></li>
			<li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
			<li><a href="#!">Second Link</a></li>
			<li><div class="divider"></div></li>
			<li><a class="subheader">Subheader</a></li>
			<li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
		</ul>
		<div id="test1" class="col s12">

			<ul class="collection">
				<li class="collection-item col s3 list-name">재밌는거
					<li class="collection-item no-padding list-name-highligh"></li>

				</li> 

				<li class="collection-item no-border-bottom img-card-box">
					<div class="row no-margin">
					<h6><?echo $data[title]; ?></h6>
					<p class="write"><span class="grey-text write-date"><?echo $data[regdate]; ?></span> <span class="blue-text write-user"><?echo $data[user_nick]; ?></span>님이 작성</p>
					</div>
					<div class="divider"></div>
					<p class="grey-text no-margin">출처 : <? if(""==$data[source]){echo "뒷북"; } else{echo $data[source];} ?></p>
				</li>
				<li class="collection-item center">

				<? echo $data[content] ?>
				</li>
			</ul>

			<ul class="collection">
				<li class="collection-item img-card-box">
					<div class="row no-margin">
					<div class="col s6" onclick="location.href='/mobile/detail.php?id=<? echo $ago_data[idx]; ?>'"><img src="http://jjaltoon.backdrum.net/uploaded/board/<? echo $ago_data[image]; ?>" width="100%">
						<span> <? echo $ago_data[title]; ?></span>
						</div>
						<div class="col s6" onclick="location.href='/mobile/detail.php?id=<? echo $next_data[idx]; ?>'">
							<img src="http://jjaltoon.backdrum.net/uploaded/board/<? echo $next_data[image]; ?>" width="100%">
							<span> <? echo $next_data[title]; ?></span>
						</div>
						
					</div>
				</li>
			</ul>
		</div>
		<div id="test2" class="col s12">Test 2</div>
		<div id="test3" class="col s12">Test 3</div>
		<div id="test4" class="col s12">Test 4</div>

		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
		<script type="text/javascript">  
			$(document).ready(function(){
				$('ul.tabs').tabs();
				$(".button-collapse").sideNav();
			});
		</script>
	</body>
	</html>  

