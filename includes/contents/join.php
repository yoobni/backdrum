<?if($MEM['idx'] > 0){?><script>alert('이미 로그인하셨습니다.');window.location.href='/';</script><?exit;}?>
<div class="join_top">
	<dl><img src="images/join_img1.png" alt="" /></dl>
	<dt>
		<h1><b>뒷북</b>에 오신것을 환영합니다!</h1>
		<p>
			대한민국 NO.1 커뮤니티 <b>뒷북</b> 입니다.
		</p>
	</dt>
</div>
<div class="join_box1">
	<dl><b>1</b> 개인 정보를 입력해 주세요.</dl>
	<dt>
		<ul>
			<li></li>
			<li><input type="text" id="id" name="id" class="w208" placeholder="아이디" />
			<li>
				<div><input type="password" id="pass" name="pass" class="w208" placeholder="비밀번호 * 영문/숫자 조합 8~16자리" maxlength="16" /><input type="password" id="pass_re" name="pass_re" class="w208 mal23" placeholder="비밀번호 확인" maxlength="16" /></div>
				<div class="join_txt1"><span>.</span> 본인 확인 (비밀번호)을 위해서 정확한 정보를 입력하셔야 합니다.</div>
			</li>
			<li><input type="text" id="name" name="name" class="w208" placeholder="이름" maxlength="8" /></li>
			<li><input type="text" id="nickname" name="nickname" class="w208" placeholder="닉네임 (2~8 자 이내여야 합니다)" maxlength="8" /></li>
			<li>
				<input type="text" id="email" name="email" class="w208" placeholder="이메일 * 예 : aaa@bbb.com" maxlength="100" /><!-- @ 
				<div class="select3">
					<select>
						<option value="선택">선택</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					  </select>
				</div>-->
				* 비밀번호 찾기 등에 사용되오니 정확한 정보를 입력해 주세요.
			</li>
			<li>
				<div>
					<input type="text" id="hp" name="hp" class="w208" placeholder="휴대폰 번호 '-' 없이 번호만 입력" maxlength="12" />
					<!--div class="select3">
						<select>
							<option value="휴대폰 번호 선택">휴대폰 번호 선택</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
						</select>
					</div>
					-
					<input type="text" class="w132" />
					-
					<input type="text" class="w132" /-->
					* 선택사항
				</div>
				<div class="join_txt1">
					<p><input type="checkbox" id="event_mail" /><label for="event_mail"> <b>[메일링 수신 동의]</b> 체크 해제 하시면 공지사항 및 이벤트 정보를 받지 못합니다.</label></p>
					<p><input type="checkbox" id="event_sms" /><label for="event_sms">  이벤트 정보 SMS 수신에 동의 합니다.</label></p>
				</div>
			</li>
		</ul>
	</dt>
</div>
<div class="join_box1">
	<dl><b>2</b> 약관에 동의해 주세요.</dl>
	<dt>
		<ul>
			<li>
				<div class="join_txt1">
					<b><input type="checkbox" id="all_check" /><label for="all_check"> [전체동의] 회원가입 약관에 모두 동의합니다.</label></b><br>
					[이용약관, 전자금융거래 이용약관, 개인정보 수집 및 이용, 개인정보의 제 3자 제공, 개인정보의 취급 위탁]
				</div>
			</li>
			<li>
				<ul class="join_box2">
					<li class="join_box2_title on">
						<p>
							<input type="checkbox" id="terms_chk" /><label for="terms_chk"> 이용약관 동의(필수) </label><!-- span>약관전체보기</span -->
							<a href="#none"></a>
						</p>
						<div>
							<?=nl2br($PMSETTING['terms'])?>
						</div>
					</li>
					<li class="join_box2_title">
						<p>
							<input type="checkbox" id="privacy_chk" /><label for="privacy_chk"> 개인정보의 제3자제공 동의(선택) </label><!-- span>약관 전체보기</span -->
							<a href="#none"></a>
						</p>
						<div>
							<?=nl2br($PMSETTING['privacy'])?>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</dt>
</div>
<div class="join_box1 borb0">
	<dl>&nbsp;</dl>
	<dt>
		<div class="join_txt1">
			<span>.</span> 뒷북은 회원간의 건전한 커뮤니티 공간입니다. 따라서 허위 정보 입력 및 타인 명의 도용, 미풍 양속에 저해하는 회원의
			가입은 거절 될 수 있으며, 회원 본인의 정확한 전화번호 및 이메일 정보를 입력하셔야 정상적인 이용이 가능합니다.<br>
			개인정보 도용 및 허위정보 입력에 대한 책임과 불이익은 회원 본인이 감수하게 되오니 개인정보 입력시 유의 하시기 바랍니다.
		</div>
	</dt>
</div>
<div class="btn_c">
	<a href="javascript:" class="join_btn2">회원가입</a><a href="/" class="join_btn3">취소</a>
</div>

<script>
$(document).ready(function() {
	$('select').wSelect();

    $("#hp").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$("#id").blur(function(){
		if($(this).val() != ''){
			if($(this).val().length < 4 || $(this).val().length > 18){
				alert('아이디는 4~18자리로 입력하셔야 합니다.');
				$(this).data('chk','none');
				$(this).val('');
			} else {
				data = "proc=id_chk&id="+$(this).val();
				$.ajax({
					type: "POST",
					url: "/includes/ajax_loads/join.php",
					cache: false,
					data: data,
					success: function(rtn){
						var code = rtn.split("||");
						if(code[0]=='already_used'){
							alert('이미 사용 중인 아이디입니다.');
							$('#id').val('').focus();
							$('#id').data('chk','none');
						}else if(code[0]=='success'){
							$('#id').data('chk','okay');
						}else{
							alert(code[0]);
							$('#id').val('').focus();
							$('#id').data('chk','none');
						}
					}
				});
			}
		} else {
			$(this).data('chk','none');
		}
	});

	$("#name").blur(function(){
		if($(this).val() != ''){
			if($(this).val().length < 2 || $(this).val().length > 8){
				alert('닉네임은 2~8자리로 입력하셔야 합니다.');
				$(this).data('chk','none');
				$(this).val('');
			} else {
				$('#name').data('chk','okay');
			}
		} else {
			$(this).data('chk','none');
		}
	});

	$("#nickname").blur(function(){
		if($(this).val() != ''){
			if($(this).val().length < 2 || $(this).val().length > 8){
				alert('닉네임은 2~8자리로 입력하셔야 합니다.');
				$(this).data('chk','none');
				$(this).val('');
			} else {
				data = "proc=nick_chk&nickname="+$(this).val();
				$.ajax({
					type: "POST",
					url: "/includes/ajax_loads/join.php",
					cache: false,
					data: data,
					success: function(rtn){
						var code = rtn.split("||");
						if(code[0]=='already_used'){
							alert('이미 사용 중인 닉네임입니다.');
							$('#nickname').val('').focus();
							$('#nickname').data('chk','none');
						}else if(code[0]=='success'){
							$('#nickname').data('chk','okay');
						}else{
							alert(code[0]);
							$('#nickname').val('').focus();
							$('#nickname').data('chk','none');
						}
					}
				});
			}
		} else {
			$(this).data('chk','none');
		}
	});

	$("#email").blur(function(){
		if($(this).val() != ''){
			var regExp = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			if(!regExp.test($(this).val())){
				alert("이메일을 정확히 입력해 주세요.");
				$(this).data('chk','none');
				$(this).val('');
			} else {
				data = "proc=email_chk&email="+$(this).val();
				$.ajax({
					type: "POST",
					url: "/includes/ajax_loads/join.php",
					cache: false,
					data: data,
					success: function(rtn){
						var code = rtn.split("||");
						if(code[0]=='already_used'){
							alert('이미 사용 중인 이메일입니다.');
							$('#email').val('');
							$('#email').data('chk','none');
						}else if(code[0]=='success'){
							$('#email').data('chk','okay');
						}else{
							alert(code[0]);
							$('#email').val('');
							$('#email').data('chk','none');
						}
					}
				});
			}
		} else {
			$(this).data('chk','okay');
		}
	});

	$("#pass").blur(function(){
		if($(this).val() != ''){
			if($(this).val().length < 8 || $(this).val().length > 16){
				//$('#pass_err').text('비밀번호는 8~16자리로 입력하셔야 합니다.');
				alert('비밀번호는 8~16자리로 입력하셔야 합니다.');
				$(this).data('chk','none');
				$(this).val('');
			} else {
				if($(this).val() == $('#pass_re').val()){
					$(this).data('chk','okay');
				} else {
					$(this).data('chk','none');
				}
			}
		} else {
			$(this).data('chk','none');
		}
	});

	$("#pass_re").focus(function(){
		if($("#pass").val() == ''){
			alert('비밀번호 먼저 입력해주세요!');
			$('#pass').focus();
		}
	});

	$("#pass_re").blur(function(){
		if($('#pass').val() != $(this).val()){
			alert('비밀번호가 일치하지 않습니다.');
			$('#pass').data('chk','none');
			$(this).val('');
		} else {
			$('#pass').data('chk','okay');
		}
	});

	$("#hp").blur(function(){
		if($(this).val() != ''){
			data = "proc=phone_chk&phone="+$('#hp').val();
			$.ajax({
				type: "POST",
				url: "/includes/ajax_loads/join.php",
				cache: false,
				data: data,
				success: function(rtn){
					var code = rtn.split("||");
					if(code[0]=='already_used'){
						alert('이미 사용 중인 휴대폰 번호입니다.');
						$('#hp').val('');
						$('#hp').data('chk','none');
					}else if(code[0]=='success'){
						$('#hp').data('chk','okay');
					}else{
						alert(code[0]);
						$('#hp').val('');
						$('#hp').data('chk','none');
					}
				}
			});
		} else {
			$(this).data('chk','okay');
		}
	});

	$('#terms_chk').change(function(){
		if($(this).is(':checked') && $('#privacy_chk').is(':checked')){
			$('#all_check').prop('checked',true);
		} else {
			$('#all_check').prop('checked',false);
		}
	});

	$('#privacy_chk').change(function(){
		if($(this).is(':checked') && $('#terms_chk').is(':checked')){
			$('#all_check').prop('checked',true);
		} else {
			$('#all_check').prop('checked',false);
		}
	});

	$('#all_check').change(function(){
		if($(this).is(':checked')){
			$('#terms_chk').prop('checked',true);
			$('#privacy_chk').prop('checked',true);
		} else {
			$('#terms_chk').prop('checked',false);
			$('#privacy_chk').prop('checked',false);
		}
	});

	$('.join_btn2').click(function(){
		if(!$('#terms_chk').is(':checked')){
			alert('이용약관에 동의해주세요!');
			return false;
		}
		if(!$('#privacy_chk').is(':checked')){
			alert('개인정보취급방침에 동의해주세요!');
			return false;
		}

		if($('#id').data('chk')!='okay'){
			alert('아이디를 다시 확인해주세요.');
			$('#id').focus();
			return false;
		}

		if($("#pass").data('chk')!='okay'){
			alert('비밀번호를 다시 확인해주세요.');
			$('#pass').val('').focus();
			return false;
		}

		if($('#name').val()==''){
			alert('이름을 입력하세요.');
			$('#name').focus();
			return false;
		}

		if($('#nickname').data('chk')!='okay'){
			alert('닉네임을 다시 확인해주세요.');
			$('#nickname').focus();
			return false;
		}

		var regExp = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		//if($('#email').val() != '' && !regExp.test($('#email').val())){
		if(!regExp.test($('#email').val())){
			alert("이메일을 정확히 입력해 주세요.");
			$('#email').data('chk','none');
			$("#email").focus();
			return false;
		}
		if($('#email').data('chk')=='none'){
			alert('이메일를 다시 확인해주세요.');
			$('#email').focus();
			return false;
		}

		$(this).hide();

		var data_add = '';

		if($('#hp').val() != '') data_add += "&phone="+$('#hp').val();

		data_add += ($('#event_mail').is(':checked'))?"&event_mail=1":"&event_mail=0";
		data_add += ($('#event_sms').is(':checked'))?"&event_sms=1":"&event_sms=0";

		var data = 'id='+$('#id').val()+'&name='+$('#name').val()+'&nickname='+$('#nickname').val()+'&pass='+$('#pass').val()+'&email='+$('#email').val()+"&phone="+$('#hp').val() + data_add;
		$.ajax({
			type: "POST",
			url: "/includes/ajax_loads/join.php",
			cache: false,
			data: data,
			success: function(rtn){
				var code = rtn.split("||");
				if(code[0]=='already_used'){
					alert('이미 가입된 아이디입니다.\n로그인 페이지로 이동하겠습니다.');
					location.href='/?inc=login';
				}else if(code[0]=='already_email'){
					alert('이미 가입된 이메일 정보입니다.\n로그인 페이지로 이동하겠습니다.');
					location.href='/?inc=login';
				}else if(code[0]=='already_phone'){
					alert('이미 가입된 핸드폰 정보입니다.\n로그인 페이지로 이동하겠습니다.');
					location.href='/?inc=login';
				}else if(code[0]=='success'){
					alert('회원가입이 완료되었습니다.');
					location.href='/';
				}else{
					alert(code[0]);//에러출력
					$('.jbtn').show();
				}
			}
		});
		return false;
	});
});
</script>