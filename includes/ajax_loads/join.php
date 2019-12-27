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
$PMLIST['NAME'] = PM_DELHTML($_REQUEST['name']);
$PMLIST['NICKNAME'] = PM_DELHTML($_REQUEST['nickname']);
$PMLIST['PASS'] = PM_DELHTML($_REQUEST['pass']);
$PMLIST['EMAIL'] = PM_DELHTML($_REQUEST['email']);
$PMLIST['GENDER'] = intval($_REQUEST['gender']);
$PMLIST['BIRTH'] = intval($_REQUEST['birth']);
$PMLIST['PHONE'] = PM_DELHTML(str_replace('-','',$_REQUEST['phone']));
$PMLIST['LUNAR'] = intval($_REQUEST['lunar']);
$PMLIST['EVENT_SMS'] = intval($_REQUEST['event_sms']);
$PMLIST['EVENT_MAIL'] = intval($_REQUEST['event_mail']);
$PMLIST['MARKETING'] = PM_DELHTML($_REQUEST['marketing']);
$PMLIST['PRIV_CONS'] = PM_DELHTML($_REQUEST['privacy_consign']);
$PMLIST['ZIP'] = PM_DELHTML($_REQUEST['zip']);

$clumn_add = ''; $value_add = '';

if($PMLIST['PROC'] == 'id_chk'){
	$already_check = mysqli_query($connect,"SELECT * FROM users WHERE id = '".$PMLIST['ID']."'");
	if(mysqli_num_rows($already_check) > 0){
		echo "already_used";
		exit;
	}
	echo "success";
} elseif($PMLIST['PROC'] == 'email_chk'){
	if($_REQUEST['shop'] == 'y') $where_add = " AND merchant = 'Y'";
	$already_email_check = mysqli_query($connect,"SELECT * FROM users WHERE email = '".$PMLIST['EMAIL']."'".$where_add);
	if(mysqli_num_rows($already_email_check) > 0){
		echo "already_used";
		exit;
	}
	echo "success";
} elseif($PMLIST['PROC'] == 'nick_chk'){
	$already_email_check = mysqli_query($connect,"SELECT * FROM users WHERE nick = '".$PMLIST['NICKNAME']."'");
	if(mysqli_num_rows($already_email_check) > 0){
		echo "already_used";
		exit;
	}
	echo "success";
} elseif($PMLIST['PROC'] == 'phone_chk'){
	if($_REQUEST['shop'] == 'y') $where_add = " AND merchant = 'Y'";
	$already_phone_check = mysqli_query($connect,"SELECT * FROM users WHERE phone = '".$PMLIST['PHONE']."'".$where_add);
	if(mysqli_num_rows($already_phone_check) > 0){
		echo "already_used";
		exit;
	}
	echo "success";
} else {
	if(!$PMLIST['ID'] || !$PMLIST['NAME'] || !$PMLIST['NICKNAME'] || !$PMLIST['PASS']){
		echo "완벽히 기재되지 않았습니다. 확인 부탁드립니다.";
		exit;
	}
	$already_check = mysqli_query($connect,"SELECT * FROM users WHERE id = '".$PMLIST['ID']."'");
	if(mysqli_num_rows($already_check) > 0){
		echo "already_used";
		exit;
	}
	if($PMLIST['EMAIL']){
		$already_email_check = mysqli_query($connect,"SELECT * FROM users WHERE email = '".$PMLIST['EMAIL']."'");
		if(mysqli_num_rows($already_email_check) > 0){
			echo "already_email";
			exit;
		}
		$clumn_add .= ",email";
		$value_add .= ",'".$PMLIST['EMAIL']."'";
	}
	if($PMLIST['PHONE']){
		$already_phone_check = mysqli_query($connect,"SELECT * FROM users WHERE phone = '".$PMLIST['PHONE']."'");
		if(mysqli_num_rows($already_phone_check) > 0){
			echo "already_phone";
			exit;
		}
		$clumn_add .= ",phone";
		$value_add .= ",'".$PMLIST['PHONE']."'";
	}
	if($PMLIST['EVENT_MAIL'] == 1){
		$clumn_add .= ",event_mail";
		$value_add .= ",'1'";
	}
	if($PMLIST['EVENT_SMS'] == 1){
		$clumn_add .= ",event_sms";
		$value_add .= ",'1'";
	}

	$clumn_add .= ",point";
	$value_add .= ",500";

	mysqli_query($connect,"INSERT INTO users (id,password,name,nick,join_time,join_ip,login_time,login_ip,login_count".$clumn_add.") VALUES ('".$PMLIST['ID']."','".MD5("5taku".$PMLIST['PASS'])."','".$PMLIST['NAME']."','".$PMLIST['NICKNAME']."',NOW(),'".$_SERVER['REMOTE_ADDR']."',NOW(),'".$_SERVER['REMOTE_ADDR']."','1'".$value_add.")");

	$result_idx = mysqli_insert_id($connect);

	if($result_idx > 0){
		$_SESSION["mem_sess"] = session_id();
		$_SESSION["mem_idx"]   = $result_idx;
		$_SESSION["mem_time"] = date('Y-m-d H:i:s');
		$_SESSION["mem_ip"]   = $_SERVER['REMOTE_ADDR'];

		echo "success";
		exit;
	}

	echo "DB 입력에 문제가 있습니다.\n관리자에게 문의하세요.";
}?>