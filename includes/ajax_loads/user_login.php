<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/function.php');
include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

$PMLIST['ID'] = PM_DELHTML($_REQUEST['id']);
$PMLIST['PASS'] = PM_DELHTML($_REQUEST['pass']);
$PMLIST['PHONE'] = str_replace('-','',PM_DELHTML($_REQUEST['phone']));
$PMLIST['EMAIL'] = PM_DELHTML($_REQUEST['email']);

// sql injection
function stripslashes_deep($var){
    $var = is_array($var)?
                  array_map('stripslashes_deep', $var) :
                  stripslashes($var);

    return $var;
}

function mysql_real_escape_string_deep($var){
    $var = is_array($var)?
                  array_map('mysql_real_escape_string_deep', $var) :
                  mysql_real_escape_string($var);

    return $var;
}

if( get_magic_quotes_gpc() ){
    if( is_array($PMLIST['ID']) )
        $PMLIST['ID'] = array_map( 'stripslashes_deep', $PMLIST['ID'] );
    if( is_array($_GET) )
        $PMLIST['PASS'] = array_map( 'stripslashes_deep', $PMLIST['PASS'] );
}

if( is_array($PMLIST['ID']) )
    $PMLIST['ID'] = array_map( 'mysql_real_escape_string_deep', $PMLIST['ID'] );
if( is_array($PMLIST['PASS']) )
    $PMLIST['PASS'] = array_map( 'mysql_real_escape_string_deep', $PMLIST['PASS']);

if($PMLIST['PROC'] == 'myarticle'){
	$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE user_idx = '".$MEM['idx']."' AND status = '0' ORDER BY regtime DESC LIMIT 10");
	if(mysqli_num_rows($board_qry) > 0){
		while($board_row = mysqli_fetch_array($board_qry)){
			$category = ($board_row['category_title'])?$board_row['category_title']:$board_row['community_category_title'];?>
		<div class="my_article_list" onclick=location.href='/?inc=<?if($board_row['type'] == '1'){?>epilogue_<?}?>view&idx=<?=$board_row['idx']?>'><span>[<?=$category?>]</span> <?=$board_row['title']?></div>
		<?}
	} else {?>
	<font>등록된 게시물이 없습니다.</font>
	<?}
} elseif($PMLIST['PROC'] == 'zzimlist'){
	$zzim_qry = mysqli_query($connect,"SELECT * FROM zzim WHERE user_idx = '".$MEM['idx']."' AND status = '0' ORDER BY regtime DESC");
	if(mysqli_num_rows($zzim_qry) > 0){
		while($zzim_row = mysqli_fetch_array($zzim_qry)){
			$login_shop_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM shop WHERE idx = '".$zzim_row['shop_idx']."'"));
			$img_exp = explode(',',$login_shop_info['image']);
			$cate_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$login_shop_info['category_idx']."'"));
			$place2_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM place2 WHERE idx = '".$login_shop_info['place2']."'"));?>
		<div class="zzim_list" onclick=location.href='/?inc=shop_detail&idx=<?=$login_shop_info['idx']?>'><span>[<?=$place2_info['name']?> - <?=$cate_info['title']?>]</span> <div class="zzim_name"><?=$login_shop_info['name']?></div><img src="/images/common/shop_home.png"></div>
		<?}
	} else {?>
	<font>등록된 찜 리스트가 없습니다.</font>
	<?}
} elseif($PMLIST['PROC'] == 'find_id'){
	$already_email_check = mysqli_query($connect,"SELECT * FROM normal_users WHERE email = '".$PMLIST['EMAIL']."'");
	if(mysqli_num_rows($already_email_check) > 0){
		$user_info = mysqli_fetch_array($already_email_check);
		echo $user_info['id'];
		exit;
	}
	echo "none";
} elseif($PMLIST['PROC'] == 'find_pw'){
	$already_email_check = mysqli_query($connect,"SELECT * FROM normal_users WHERE email = '".$PMLIST['EMAIL']."'");
	if(mysqli_num_rows($already_email_check) > 0){
		$user_info = mysqli_fetch_array($already_email_check);

		$kwd[0] = array('0','0','E','T');
		$kwd[1] = array('1','1','F','P');
		$kwd[2] = array('2','2','G','Q');
		$kwd[3] = array('3','3','H','R');
		$kwd[4] = array('4','4','I','S');
		$kwd[5] = array('5','5','J','O');
		$kwd[6] = array('6','A','K','U');
		$kwd[7] = array('7','B','L','V');
		$kwd[8] = array('8','C','M','W');
		$kwd[9] = array('9','D','N','X');

		$rand_num = mt_rand(10000, 99999);

		$n = $rand_num;
		$n = sprintf('%06d',$n);
		$n = strval($n);
		$arr_key = '';
		for($i=0; $i<strlen($n); $i++) {
			$rand_key = array_rand($kwd[$n[$i]],1);
			$arr_key .= $kwd[$n[$i]][$rand_key];
		}

		mysqli_query($connect,"INSERT INTO find_pw_code SET user_idx = '".$user_info['idx']."', id = '".$user_info['id']."', name = '".$user_info['name']."', code = '".$arr_key."', email = '".$PMLIST['EMAIL']."', regdate = NOW()");
		mysqli_query($connect,"UPDATE normal_users SET password = '".MD5("danawa".$arr_key)."' WHERE email = '".$PMLIST['EMAIL']."'");

		echo "메일이 1분~5분 사이로 발송됩니다.\n발송되는 임시 비밀번호로 로그인 후 사용하실 비밀번호로 변경해주시기 바랍니다.";

		exit;
	}
	echo "none";
} elseif($PMLIST['PROC'] == 'find_pw2'){
	$already_email_check = mysqli_query($connect,"SELECT * FROM normal_users WHERE email = '".$PMLIST['EMAIL']."'");
	if(mysqli_num_rows($already_email_check) > 0){
		$user_info = mysqli_fetch_array($already_email_check);
		$_SESSION['edit_idx'] = $user_info['idx'];
		echo "인증이 완료되었습니다. 비밀번호를 변경해주세요!";
	}
	echo "none";
} else {
	$ip_chk = mysqli_query($connect,"SELECT * FROM banip WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND status = '0'");
	if(mysqli_num_rows($ip_chk) <= 0){
		$id_chk = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM normal_users WHERE id = '".$PMLIST['ID']."' AND password = '".MD5("5taku".$PMLIST['PASS'])."' AND status != '1' AND leavedate IS NULL"));
		if($id_chk['idx'] > 0){
			$sql  = "update `normal_users` set ";
			$sql .= " `login_ip`='".$_SERVER['REMOTE_ADDR']."',";
			$sql .= " `login_count`= `login_count` + 1,";
			$sql .= " `login_time`='".date('Y-m-d H:i:s')."'";
			$sql .= " where `idx`='".$id_chk['idx']."'";
			mysqli_query($connect,$sql);

			$_SESSION["mem_sess"] = session_id();
			$_SESSION["mem_idx"]  = $id_chk['idx'];
			$_SESSION["mem_time"] = date('Y-m-d H:i:s');
			$_SESSION["mem_ip"]   = $_SERVER['REMOTE_ADDR'];
      $_SESSION["mem_user"] = session_id();

			echo "success||".$id_chk['name'];
			exit;
		}
		echo "입력하신 아이디나 비밀번호가 맞지 않습니다.\n만약 지속적인 문제가 발생할 경우,\n아이디/비밀번호 찾기를 이용해주세요.";
	} else {
		echo "차단되었습니다.";
	}
}?>
