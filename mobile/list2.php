<?php
require_once ("connect.php");

require_once ("list_query.php");
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

		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>

	<body>
		<nav class="nav-extended white black-text">
			<div class="nav-wrapper ">
				<a href="/mobile" class="brand-logo"><img src="http://jjaltoon.backdrum.net/images/logo.png" width="80px"></a>
				<a onclick="history.back()"  class="button-collapse"><i class="material-icons grey-text">arrow_back</i></a>
				<ul id="nav-mobile" class="right hide-on-med-and-down ">
					<li><a href="sass.html" class="black-text">Sass</a></li>
					<li><a href="badges.html">Components</a></li>
					<li><a href="collapsible.html">JavaScript</a></li>
				</ul>
				<ul class="side-nav" id="mobile-demo">

				<li class="blue white-text">실시간 인기 뒷북</li>
				
					
				</ul>
			</div>
			</div>
			
			<div class="nav-content">
				
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

			<ul class="collection">
				<li class="collection-item col s3 list-name">
				<? $title=mysqli_fetch_array($list_query); 
				echo "게시물 리스트";
				?>
					<li class="collection-item no-padding list-name-highligh"></li>

				</li> 
				<li class="collection-item img-card-box">
					<div class="row no-margin">
						<?php
						while ($data =mysqli_fetch_array($list_query)){
							?>
							<div class="col s6" onclick="location.href='/mobile/detail.php?id=<? echo $data[idx]; ?>'">
							<div class="thumbnail">
								<img src="/uploaded/board/<?echo $data[image];
								if($data[image]==""){
									echo"defailt.png";
								}
								?>"  width="100%"">
								</div>
								<p class="img-title jumjumjum"> <?echo $data[title];?><br/><span class="writer">
									<i class="material-icons writer">edit</i> <?echo $data[user_nick];?>
								</span></p>

							</div>
							<?php
						}

						?>  
					</div>
				</li>
			</ul>
			<ul class="pagination center">
				<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
				<li class="waves-effect"><? if(($_GET['page'])>2) { ?>
				<a href="/mobile/list.php?category=<? echo $_GET[category]; ?>&page=<? echo $_GET['page']-2; ?>"><? echo $_GET['page']-2; ?></a>
				<? } ?>
				</li>
				<li class="waves-effect"><? if(($_GET['page'])>1) { ?>
				<a href="/mobile/list.php?category=<? echo $_GET[category]; ?>&page=<? echo $_GET['page']-1; ?>"><? echo $_GET['page']-1; ?></a>
				<? } ?>
				</li>
				<li class="waves-effect blue"><a class="white-text" href="/mobile/list.php?category=<? echo $_GET[category]?>&page=<? echo $_GET['page'] ?>"><? echo $_GET['page'] ?></a></li>
				<li class="waves-effect"><a href="/mobile/list.php?category=<? echo $_GET[category]?>&page=<? echo $_GET['page']+1 ?>"><? echo $_GET['page']+1 ?></a></li>
				<li class="waves-effect"><a href="/mobile/list.php?category=<? echo $_GET[category]?>&page=<? echo $_GET['page']+2 ?>"><? echo $_GET['page']+2 ?></a></li>
				<li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
			</ul>
		<div id="test1" class="col s12">


		</div>

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

