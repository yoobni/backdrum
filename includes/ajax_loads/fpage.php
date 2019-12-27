<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/function.php');
include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

if($PMLIST['PROC'] == 'insert'){
	$PMLIST['TITLE'] = PM_DELHTML($_REQUEST['title']);
	$PMLIST['CODE'] = PM_DELHTML($_REQUEST['code']);
	//$PMLIST['META'] = $_REQUEST['meta'];
	$PMLIST['PAGE_ID'] = PM_DELHTML($_REQUEST['page_id']);

	mysqli_query($connect, "INSERT INTO facebook_page SET title = '".$PMLIST['TITLE']."', code = '".$PMLIST['CODE']."', page_id = '".$PMLIST['PAGE_ID']."' ON DUPLICATE KEY UPDATE title = '".$PMLIST['TITLE']."', code = '".$PMLIST['CODE']."', meta = '".$PMLIST['META']."', status = '0'"); // meta = '".$PMLIST['META']."',

	if(mysqli_insert_id($connect) > 0){
		echo "success";
	} else {
		echo "디비 입력 중 문제가 발생하였습니다.";
	}
} elseif($PMLIST['PROC'] == 'delete'){
	mysqli_query($connect, "UPDATE facebook_page SET status = '1' WHERE idx = ".$PMLIST['IDX']);
	echo "success";
} elseif($PMLIST['PROC'] == 'list'){
	$sql = mysqli_query($connect, "SELECT * FROM facebook_page WHERE status = '0'");
	if(mysqli_num_rows($sql) > 0){ while($row = mysqli_fetch_array($sql)){?>
			<tr>
				<td><?=$row['title']?></td>
				<td><?=$row['code']?></td>
				<!--td><input type="text" value='<?=$row['meta']?>' readonly></td-->
				<td><?=$row['app_id']?></td>
				<td><?=$row['app_secret']?></td>
				<td><?=$row['page_id']?></td>
				<td><?=$row['audiencenetwork']?></td>
				<td><?=$row['audiencenetwork_l']?></td>
				<td style="text-align:center; cursor:pointer; color:#d90000; font-weight:600;" title="<?=$row['title']?>" idx="<?=$row['idx']?>" class="delete_fpage">삭제</td>
			</tr>
	<?}}
} elseif($PMLIST['PROC'] == 'page_select'){?>
	<option value="">페이지 선택</option>
	<?$sql = mysqli_query($connect, "SELECT idx, title FROM facebook_page WHERE status = '0'");
	if(mysqli_num_rows($sql) > 0){ while($row = mysqli_fetch_array($sql)){?>
	<option value="<?=$row['idx']?>"><?=$row['title']?></option>
	<?}}
} elseif($PMLIST['PROC'] == 'app'){
	$PMLIST['APP_ID'] = PM_DELHTML($_REQUEST['app_id']);
	$PMLIST['APP_SECRET'] = PM_DELHTML($_REQUEST['app_secret']);
	$PMLIST['ANID'] = PM_DELHTML($_REQUEST['anid']);
	$PMLIST['ANLID'] = PM_DELHTML($_REQUEST['anlid']);
	mysqli_query($connect,"UPDATE facebook_page SET app_id = '".$PMLIST['APP_ID']."', app_secret = '".$PMLIST['APP_SECRET']."', audiencenetwork = '".$PMLIST['ANID']."', audiencenetwork_l = '".$PMLIST['ANLID']."' WHERE idx = ".$PMLIST['IDX']);
	echo "success";
} elseif($PMLIST['PROC'] == 'page_checkbox'){
	$PMLIST['IDX'] = intval($_REQUEST['article_idx']);
	$page_idxs = array();
	if($PMLIST['IDX'] > 0){
		$sql = mysqli_query($connect,"SELECT * FROM board WHERE idx = ".$PMLIST['IDX']);
		if(mysqli_num_rows($sql) > 0){
			$bdata = mysqli_fetch_array($sql);
			$page_idxs = explode('|',$bdata['facebook_page']);
		} else {
			exit;
		}
	}
	$sql = mysqli_query($connect,"SELECT * FROM facebook_page WHERE status = '0'");
	if(mysqli_num_rows($sql) > 0){ while($row = mysqli_fetch_array($sql)){
		$checked = false;
		if(in_array($row['idx'],$page_idxs)) $checked = true;
		?>
			<label for="fp_<?=$row['idx']?>"><input type="checkbox" id="fp_<?=$row['idx']?>" name="facebook_page[]" value="<?=$row['idx']?>"<?if($checked){?> checked<?}?>> <?=$row['title']?></label>
	<?}}
}?>
