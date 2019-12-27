<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

$board_qry = mysql_query("SELECT *, DATE_FORMAT(regtime,'%Y-%m-%d') AS regdate FROM board WHERE idx = '".$PMLIST['IDX']."' AND type = '0'");
if(mysql_num_rows($board_qry) > 0)$board_info = mysql_fetch_array($board_qry);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko">
<head>
	<link rel="stylesheet" type="text/css" href="/css/content.css" />
</head>
<body>
<?=$board_info['content']?>
</body>
</html>