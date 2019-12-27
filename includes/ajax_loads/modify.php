<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

$filter_arr = json_decode(str_replace('\\"','"',$PMLIST['FILTER']), true);
$opass = PM_DELHTML($filter_arr['opass']);
$npass = PM_DELHTML($filter_arr['npass']);
$spass = PM_DELHTML($filter_arr['spass']);
$hp = PM_DELHTML($filter_arr['hp']);
$email = PM_DELHTML($filter_arr['email']);
$messenger = PM_DELHTML($filter_arr['messenger']);
$messenger_service = PM_DELHTML($filter_arr['messenger_service']);

/*if($password) $pwd_set = "password = MD5('maincoupon".$password."'),";

$mem_info = sqlsrv_fetch_array(sqlsrv_query($connect, "SELECT * FROM users WHERE IdMaster = '".$MEM['IdMaster']."'"));

$sql = "INSERT INTO users_prev SET
				id					= '".$mem_info['id']."',
				password			= '".$mem_info['password']."',
				level				= '".$mem_info['level']."',
				name				= '".$mem_info['name']."',
				phone				= '".$mem_info['phone']."',
				email				= '".$mem_info['email']."',
				messenger			= '".$mem_info['messenger']."',
				messenger_service	= '".$mem_info['messenger_service']."',
				gender				= '".$mem_info['gender']."',
				birth				= '".$mem_info['birth']."',
				bank				= '".$mem_info['bank']."',
				bank_name			= '".$mem_info['bank_name']."',
				bank_account		= '".$mem_info['bank_account']."',
				regtime				= NOW()";
mysqli_query($connect,$sql);*/

if($PMLIST['PROC'] == 'info_modify'){
	$sql = "UPDATE User_Master SET
					[Mobile]			= N'".$hp."',
					[Email]				= N'".$email."'
				WHERE IdMaster = ".$MEM['IdMaster'];
	sqlsrv_query($connect, $sql);
	echo "ok";
} else if($PMLIST['PROC'] == 'pass_modify'){
	if(strtoupper(MD5($opass)) == $MEM['Pwd']){
		$sql = "UPDATE User_Master SET
						[Pwd]			= N'".strtoupper(MD5($npass))."'
					WHERE IdMaster = ".$MEM['IdMaster'];
		sqlsrv_query($connect, $sql);
		echo "ok";
	} else {
		echo "기존 비밀번호가 틀렸습니다.\n다시 확인 후 입력해주세요!";
	}
} else if($PMLIST['PROC'] == 'secession'){
	if(strtoupper(MD5($spass)) == $MEM['Pwd']){
		$sql = "UPDATE User_Master SET
						[IdRole]			= N'2',
						[MemberClass]		= N'탈퇴',
						[LeaveDate]			= GetDate() 
					WHERE IdMaster = ".$MEM['IdMaster'];
		sqlsrv_query($connect, $sql);

		session_unset();     // 현재 연결된 세션에 등록되어 있는 모든 변수의 값을 삭제한다
		session_destroy();  //현재의 세션을 종료한다

		echo "ok";
	}
}
?>