<?if($MEM['idx'] > 0){ // 로그인 후?>
<div class="login_after">
	<div class="login_box2">
		<?$thumb_img = (!$MEM['thumb'])?'/images/pic.jpg':'/uploaded/thumb/user/'.$MEM['thumb'];?>
		<dl><div class="thumb" style="background-image:url(<?=$thumb_img?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$thumb_img?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$thumb_img?>', sizingMethod='scale');" alt="" /></dl>
		<dl>
			<ul>
				<li class="login_box2_1">
					<b><?=($MEM['nick'])?$MEM['nick']:$MEM['name']?></b> 님
					<a href="/?inc=logout">로그아웃</a>
				</li>
				<li class="login_box2_3">내 포인트 : <a href="/?inc=point"><?=number_format($MEM['point'])?></a> 점</li>
				<li class="login_box2_4">계급 : <?=$MEM['user_level']?><?if($MEM['level'] == 99){?>(관리자)<?}?></li>
			</ul>
		</dl>
	</div>
</div>
<?} else { // 로그인 전?>
<div class="login_before">
	<div class="login_title">로그인</div>
	<ul class="login_box1">
		<li class="login_box1_1">
			<span>
				<input id="id" type="text" value="<?=$_COOKIE['save_id']?>" placeholder="아이디" />
				<input id="pass" type="password" placeholder="비밀번호">
			</span><a id="login_btn" href="javascript:">로그인</a>
		</li>
		<li class="login_box1_2">
			<input type="checkbox" id="save_id"<?if($_COOKIE['save_id']){?> checked<?}?> /><label for="save_id">아이디 저장</label>
		</li>
	</ul>
	<div class="txt1"><a href="/?inc=join">뒷북 무료 회원가입</a></div>
</div>

<script>
$(document).ready(function() {
    $("#id,#pass").bind("keydown", function(e) {
		if (e.keyCode == 13) { // enter key
			$("#login_btn").click();
			return false
		}
	});
	$("#login_btn").click(function(){
		$('#pass').val($('#pass').val().replace(/-/g,''));

		if($('#id').val()==''){
			alert('아이디를 입력하세요.');
			$('#id').focus();
			return false;
		}
		if($('#pass').val()==''){
			alert('비밀번호를 입력하세요.');
			$('#pass').focus();
			return false;
		}
		//$('#lbtn').hide();

		if($('#save_id').prop('checked')){
			$.cookie("save_id", $('#id').val(), {
			   expires : 999,						//expires in 10 days
			   path    : '/',						//The value of the path attribute of the cookie 
													//(default: path of page that created the cookie).
			   domain  : '<?=$_SERVER['HTTP_HOST']?>',	//The value of the domain attribute of the cookie
													//(default: domain of page that created the cookie).
			   secure  : false						//If set to true the secure attribute of the cookie
													//will be set and the cookie transmission will
													//require a secure protocol (defaults to false).
			});
		}
		
		var data = 'id='+$('#id').val()+'&pass='+$('#pass').val();
		$.ajax({
			type: "POST",
			url: "/includes/ajax_loads/login.php",
			cache: false,
			data: data,
			success: function(rtn){
				var code = rtn.split("||");
				if(code[0]=='success'){
					alert(code[1]+'님 로그인이 완료되었습니다.');
					location.href='/?inc=write';
				}else{
					alert(code[0]);//에러출력
					//$('#lbtn').show();
				}
			}
		});
		return false;
	});
	$('#save_id').change(function(){
		if($(this).prop('checked')){
			$.cookie("save_id", $('#id').val(), {
			   expires : 999,						//expires in 10 days
			   path    : '/',						//The value of the path attribute of the cookie 
													//(default: path of page that created the cookie).
			   domain  : '<?=$_SERVER['HTTP_HOST']?>',	//The value of the domain attribute of the cookie
													//(default: domain of page that created the cookie).
			   secure  : false						//If set to true the secure attribute of the cookie
													//will be set and the cookie transmission will
													//require a secure protocol (defaults to false).
			});
		} else {
			$.removeCookie("save_id");
		}
	});
});
</script>
<?}?>