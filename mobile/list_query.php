<?
#비정상적인 접근 방지
function isInteger($input){
	return(ctype_digit(strval($input)));
}

if(isInteger($_GET['category'])&&isInteger($_GET['page'])){
	$boardcount_offset=$_GET[page]*20-20;

	$list_query= mysqli_query($connect, "SELECT * FROM board WHERE status = '0' AND `category_idx` = ". $_GET['category'] ." ORDER BY `idx` DESC LIMIT 20 OFFSET ". $boardcount_offset);
	if($_GET['category']==0)
	{
		$list_query= mysqli_query($connect, "SELECT * FROM `board` WHERE status = '0' ORDER BY `idx` DESC LIMIT 20 OFFSET ". $boardcount_offset);
	}

}

else if(is_null($_GET['category'])||is_null($_GET['page'])){
	echo "<script>alert('정상적인 방법으로 접근하시기 바랍니다.');history.back()</script>";
}
else{
	echo "<script>alert('정상적인 방법으로 접근하시기 바랍니다.');history.back()</script>";
}
