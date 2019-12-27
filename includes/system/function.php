<?

/*
Custom Function
 
0. PM_HEADER()
	- 공용해더

1. PM_DBCONNECT()
	- DB연결시 사용한다.

2. PM_PNG("주소", (0~1)옵션)
	- PNG파일의 브라우저간의 호환성을 보장한다. IE6 포함

3. PM_DELHTML("글")
	- HTML을 제거한다.

4. PM_PICKUP("글", "시작", "끝", 인덱스)
	- 중간에 특정 <div> 나 태그 사이에 있는 문자를 추출한다.

5. PM_ERROR("에러메세지")
	- 에러 발생시 안내 메세지를 출력한다.

6. PM_RANDMAP(시작, 종료)
	- 시작값과 종료값 사이의 숫자를 랜덤배열로 반환한다.

7. PM_AGERETURN(숫자)
	- 2자리의 연도를 나이로 변환해준다.

8. PM_FILESISE(숫자)
	- 바이트 단위의 숫자를 용량으로 변환하여 출력한다.

9. PM_UTF8CUT("글", 길이, 바이트인식, "후미첨가")
	- UTF-8에 대한 길이를 컷트해 준다.
	바이트인식값을 true 로 바꾼다면 3바이트 한글을 2바이트로 인식해서 출력하게 판단하게 된다.

10. PM_CHECKIMG(유무)
	- 1값이면 체크 0값이면 체크되지 않은 이미지가 출력된다.

11. PM_PAGELIST(현재페이지, 전체글수, 리스트수, 이동가능한리스트수, 실행스크립트)
	- 각 게시판의 하단 리스트 페이징을 합니다.

12. PM_SEIMG(성별)
	- 1, 3, 5, M 값이면 남자 아이콘이 출력된다. / 2, 4, 6, F 면 여자 아이콘이 출력된다.

13. PM_STRONG(텍스트, 비교값2, 비교값2)
	- 2개의 값이 같으면 <strong> 태그가 적용된다.

14. PM_PAGEREPAIR(현재페이지, 전체글수, 리스트수)
	- [리턴값 있음] 실제 게시물 보다 페이지가 초과되는 경우 보정을 해준다. 

15. PM_MEMBER()
	- 세션값에서 회원정보를 가져온다.



*/

/**
 * Get Video ID
 * 유투브 주소에서 Video ID를 추출합니다.
 *
 * @access  public
 * @param   string
 * @return  string
 */
if ( ! function_exists( 'get_video_id' ) ){
    function get_video_id( $str ){
        if( substr( $str, 0, 4 ) == 'http' ){
            if( strpos( $str, 'youtu.be' ) ){
                return array_pop( explode( '/', $str ) );
            }else if( strpos( $str, '/embed/' ) ){
                return array_pop( explode( '/', $str ) );
            }else if( strpos( $str, '/v/' ) ){
                return array_pop( explode( '/', $str ) );
            }else{
                $params = explode( '&', array_shift( explode( '#', array_pop( explode( '?', $str ) ) ) ) );
                foreach( $params as $data )
                {
                    $arr = explode( '=', $data );
                    if( $arr[ 0 ] == 'v' )
                    {
                        return $arr[ 1 ];
                    }
                }
            }
        }else{
            return $str;
        }
        return '';
    }
}
 
/**
 * Get Thumbnail URL
 * 썸네일 주소를 가져옵니다. 기본값은 default 입니다.
 *
 * @param $url_or_id
 * @param $type
 * @return string
 */
if ( ! function_exists( 'get_youtube_thumb' ) ){
    function get_youtube_thumb( $url_or_id ){
    	return '//img.youtube.com/vi/'.get_video_id( $url_or_id ).'/default.jpg';
    }
}

/* 1. DB연결함수 */
function PM_DBCONNECT() {
	global $connect;
	@mysqli_close($connect);
	$connect = @mysqli_connect("127.0.0.1","backdrum","backdrum12") or PM_ERROR("DB 접속시 에러가 발생했습니다. 접속자가 폭주중이거나 잠시 점검중일수 있습니다.", "관리자에게 문의하시기 바랍니다.");
	@mysqli_select_db($connect, "backdrum") or PM_ERROR("DB Select 에러가 발생했습니다", "관리자에게 문의하시기 바랍니다.");
	@mysqli_query($connect, "SET CHARACTER SET utf8");
	unset( $GLOBALS['conf']['db_db'], $GLOBALS['conf']['db_host'], $GLOBALS['conf']['db_id'], $GLOBALS['conf']['db_pw'] );
	return $connect;
}

/* 2. 이미지 커스텀 함수 */
function PM_PNG($url, $set=0) {
	global $USERX, $DIGISYSTEM;
	if(eregi("MSIE",$_SERVER[HTTP_USER_AGENT]) == 1) {
		?>FILTER: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=ture, sizingMethod=<? if($set == 1) { ?>crop<? } else { ?>scale<? } ?>, src=<?=$DIGISYSTEM['IMAGE_URL']."/".$url?>);<?
	} else {
		?>background:url(<?=$DIGISYSTEM['IMAGE_URL']."/".$url?>);<?
	}
}

  /* 3. 문자형 커스텀 함수 */
function PM_DELHTML($str) {
	$str = trim($str);
	if($str == "undefined") { $str = ""; }
	$enc = mb_detect_encoding($str, array("UTF-8", "EUC-KR"));
	if($str != 'UTF-8'){
		$str = iconv($enc, "UTF-8", $str);
	}
	$search = array ('@<script[^>]*?>.*?</script>@si', '@<[/!]*?[^<>]*?>@si', '@&(quot|#34);@i', '@&(amp|#38);@i', '@&(lt|#60);@i', '@&(gt|#62);@i',
	'@&(nbsp|#160);@i','@&(iexcl|#161);@i','@&(cent|#162);@i','@&(pound|#163);@i','@&(copy|#169);@i','@&#(d+);@');
	$replace = array ('','','"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(1)');
	return preg_replace_callback($search, create_function('$matches', 'return $replace;'), $str);
}

/* 4. 특정영역의 값을 추출하는 함수 */
function PM_PICKUP($str, $start, $end, $index) {
	$str2 = explode($start, $str);
	$str3 = explode($end, $str2[$index]);
	return $str3[0];
}

/* 5. 에러처리 함수 */
function PM_ERROR($str, $str2) { 
	?><script> alert("<?=$str?>\n<?=$str2?>"); </script><? exit;
}

/* 6. 중복되지 않는 랜덤을 구현하는 함수 */
function PM_RANDMAP($start, $end) {
	if($start < $end) {
		$i = $start;
		while($i <= $end) {
			$no = rand($start, $end);
			$black = 0;
			for($j=$start;$j<=$end;$j++) { if($RMap[$j] == $no) { $black = 1; } }
			if($black != 1) { $RMap[$i] = $no; $i++; }
		}
		return $RMap;
	} else {
		return 0;
	}
}

/* 7. 나이값을 반환하는 함수 */
function PM_AGERETURN($level) {
	if($level < 30) { return (date("Y") - ($level + 2000));  } 
	else {  return (date("Y") - ($level + 1900)); } 
}

/* 8. 파일크기를 함수 */
function PM_FILESISE($size) {
	if(!$size) return "0 Byte";
	if($size<1024) { 
		return ($size." Byte");
	} elseif($size >1024 && $size< 1024 *1024)  {
		return sprintf("%0.1f KB",$size / 1024);
	}
	else return sprintf("%0.2f MB",$size / (1024*1024));
}

/* 9. UTF-8 문자열을 컷트하는 함수 */
function PM_UTF8CUT($str, $len, $checkmb=false, $tail='...') {
	$len += 3;
	preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
	$m = $match[0];
	$slen = strlen($str); // length of source string
	$tlen = strlen($tail); // length of tail string
	$mlen = count($m); // length of matched characters
	if ($slen <= $len) return $str;
	if (!$checkmb && $mlen <= $len) return $str;
	$ret = array();
	$count = 0;
	for ($i=0; $i < $len; $i++) {
		$count += ($checkmb && strlen($m[$i]) > 1)?2:1;
		if ($count + $tlen > $len) break;
		$ret[] = $m[$i];
	}
	return join('', $ret).$tail;
}

/* 15. 회원정보 불러오기 */
function PM_MEMBER() {
	global $_SESSION, $connect;
	if($_SESSION[mem_idx]) {
		$MEM = mysqli_fetch_array(mysqli_query($connect,"SELECT *, DATE_FORMAT(login_time,'%Y.%m.%d %H:%i') ltime, DATE_FORMAT(join_time,'%Y.%m.%d %H:%i') jtime FROM users WHERE idx = '".$_SESSION[mem_idx]."'"));
		if(!$MEM['idx']) {
			unset($MEM);
			$MEM[level] = 0;
		}
	} else $MEM[level] = 0;
	return $MEM;
}

/* 17. 로그아웃 */
function PM_LOGINOUT($returnurl) {
	// 로그아웃처리   
	session_destroy();
	?><meta http-equiv="refresh" content="0;url=<?=$returnurl?>"><?
}

/* 19. 이미지 리사이징 */
function PM_RESIZEIMG($TEXT, $width = 700, $height = 700) {
	$TEXT = str_replace("<img ", "<img onload='javascript:resizeImg(this, ".$width.", ".$height.");' ", $TEXT);
	$TEXT = str_replace("<IMG ", "<IMG onload='javascript:resizeImg(this, ".$width.", ".$height.");' ", $TEXT);
	return $TEXT;
}

/* 20. 랜덤 1바이트문자 반환 */
function PM_RANDCHAR() {
	$value = rand(1, 32);
	// 0과 1은 영문자와 혼동될 수 있으니 제외한다.
	if($value == "1") { $value = "2"; }
	if($value == "2") { $value = "3"; }
	if($value == "3") { $value = "4"; }
	if($value == "4") { $value = "5"; }
	if($value == "5") { $value = "6"; }
	if($value == "6") { $value = "7"; }
	if($value == "7") { $value = "8"; }
	if($value == "8") { $value = "9"; }
	if($value == "9") { $value = "A"; }
	if($value == "10") { $value = "B"; }
	if($value == "11") { $value = "C"; }
	if($value == "12") { $value = "D"; }
	if($value == "13") { $value = "E"; }
	if($value == "14") { $value = "F"; }
	if($value == "15") { $value = "G"; }
	if($value == "16") { $value = "H"; }
	if($value == "17") { $value = "J"; }
	if($value == "18") { $value = "K"; }
	if($value == "19") { $value = "L"; }
	if($value == "20") { $value = "M"; }
	if($value == "21") { $value = "N"; }
	if($value == "22") { $value = "P"; }
	if($value == "23") { $value = "Q"; }
	if($value == "24") { $value = "R"; }
	if($value == "25") { $value = "S"; }
	if($value == "26") { $value = "T"; }
	if($value == "27") { $value = "U"; }
	if($value == "28") { $value = "V"; }
	if($value == "29") { $value = "W"; }
	if($value == "30") { $value = "X"; }
	if($value == "31") { $value = "Y"; }
	if($value == "32") { $value = "Z"; }
	return $value;
}

/* 20. 랜덤 1바이트문자 반환 */
/*function PM_CHECKSUM_CREATE() {
	mysqli_query($connect, " INSERT INTO `checksum` (`checkchar`, `regdate`) VALUES ('".PM_RANDCHAR().PM_RANDCHAR().PM_RANDCHAR().PM_RANDCHAR()."', '".mktime()."');" );
	return mysqli_insert_id($connect);
}*/

/* 21. 랜덤 1바이트문자 반환 */
function PM_ID_CHECK($str) {
	$str = trim($str);
	if(strlen($str) >= 6) {
		if(preg_match("/[\xA1-\xFE\xA1-\xFE]/",$str)) { 
			return 1;
		} else if(preg_match("/[!#$%^&*()?+=\/]/",$str)) {
			return 1;
		} else {
			return 2;
		}
	} else {
		return 0;
	}
}

/* 22. 패스워드체크 (한, 영 체크) */
function PM_PASS_CHECK($str) {
	if(preg_match("/[a-zA-Z]/",$str)) $chk1 = 1;
	if(preg_match("/[0-9]/",$str)) $chk2 = 1;
	if($chk1 == 1 && $chk2 == 1) { return 1; } else { return 0; }
}

/* 23. 주민번호 로직 */
function PM_JUMIN_CHECK($jumin){
	$sex  = array("1"=>"19", "2"=>"19", "3"=>"20", "4"=>"20"); 
	$birth = intval($sex[substr($jumin,6,1)].substr($jumin,0,2)); 
	if($birth > date('Y')) return false; 
	$weight = "234567892345"; // 자리수 weight 지정 
	$len = strlen($jumin);$sum = 0; 
	if($len<>13) return false; 
	for($i=0; $i<12; $i++) { $sum=$sum+(substr($jumin,$i,1)*substr($weight,$i,1)); } 
	$rst=$sum%11; $result=11-$rst; 
	if($result==10) $result=0; elseif($result==11) $result = 1; 
	$ju13 = substr($jumin,12,1); 
	if ($result <> $ju13) return false; 
	return true; 
}

function PM_MAILSEND($to, $name, $from, $subject, $message) {

	$admin_email = $from;
	$admin_name  = iconv("utf-8","euc-kr",$name);

	$mailto = $to;
	$CONTENT = iconv("utf-8","euc-kr",$message);
	$SUBJECT = iconv("utf-8","euc-kr",$subject);

	$header  = "Return-Path: ".$admin_email."\n";
	$header .= "From: =?EUC-KR?B?".base64_encode($admin_name)."?= <".$admin_email.">\n";
	$header .= "MIME-Version: 1.0\n";
	$header .= "X-Priority: 3\n";
	$header .= "X-MSMail-Priority: Normal\n";
	$header .= "X-Mailer: FormMailer\n";
	$header .= "Content-Transfer-Encoding: base64\n";
	$header .= "Content-Type: text/html;\n \tcharset=euc-kr\n";
	//$header.="cc:birthdayarchive@php.net\n";  //CC to
	//$header.="bcc:kim3001@hanmail.net,rocio79@naver.com\n"; //BCCs to

	$subject  = "=?EUC-KR?B?".base64_encode($SUBJECT)."?=\n";
	$contents = $CONTENT;

	$message = base64_encode($contents);
	//flush();
	mail($mailto, $subject, $message, $header);

}


/*
	//한글체크
	if(preg_match("/[\xA1-\xFE\xA1-\xFE]/",$obj)) $chk = 0;
	//영문체크
	if(preg_match("/[a-zA-Z]/",$obj)) $chk = 0;
	//숫자체크
	if(preg_match("/[0-9]/",$obj)) $chk = 0;
	//특수문자체크
	if(preg_match("/[!#$%^&*()?+=\/]/",$obj)) $chk = 0;
*/

function PM_GETBROWSER() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
 
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac'; }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }
     
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { $bname = 'Internet Explorer'; $ub = "MSIE"; } 
    elseif(preg_match('/Firefox/i',$u_agent)) { $bname = 'Mozilla Firefox'; $ub = "Firefox"; } 
    elseif(preg_match('/Chrome/i',$u_agent)) { $bname = 'Google Chrome'; $ub = "Chrome"; } 
    elseif(preg_match('/Safari/i',$u_agent)) { $bname = 'Apple Safari'; $ub = "Safari"; } 
    elseif(preg_match('/Opera/i',$u_agent)) { $bname = 'Opera'; $ub = "Opera"; } 
    elseif(preg_match('/Netscape/i',$u_agent)) { $bname = 'Netscape'; $ub = "Netscape"; } 
     
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
     
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){ $version= $matches['version'][0]; }
        else { $version= $matches['version'][1]; }
    }
    else { $version= $matches['version'][0]; }
     
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array('userAgent'=>$u_agent, 'name'=>$bname, 'version'=>$version, 'platform'=>$platform, 'pattern'=>$pattern);
}

/* SOAP to XML */
// FUNCTION TO MUNG THE XML
function mungXML($xml){
    // A REGULAR EXPRESSION TO MUNG THE XML
	$rgx
	= '#'           // REGEX DELIMITER
	. '('           // GROUP PATTERN 1
	. '\<'          // LOCATE A LEFT WICKET
	. '/{0,1}'      // MAYBE FOLLOWED BY A SLASH
	. '.*?'         // ANYTHING OR NOTHING
	. ')'           // END GROUP PATTERN
	. '('           // GROUP PATTERN 2
	. ':{1}'        // A COLON (EXACTLY ONE)
	. ')'           // END GROUP PATTERN
	. '#'           // REGEX DELIMITER
	;
	// INSERT THE UNDERSCORE INTO THE TAG NAME
	$rep
	= '$1'          // BACKREFERENCE TO GROUP 1
	. '_'           // LITERAL UNDERSCORE IN PLACE OF GROUP 2
	;
	// PERFORM THE REPLACEMENT
	$xml = preg_replace($rgx, $rep, $xml);

	// FIX THE URLS
	$xml = str_replace('https_//', 'https://', $xml);
	return $xml;
}

/* utf8 문자 길이 함수 */
function utf8_length($str) {
	$len = strlen($str);
	for ($i = $length = 0; $i < $len; $length++) {
		$high = ord($str{$i});
		if ($high < 0x80)//0<= code <128 범위의 문자(ASCII 문자)는 인덱스 1칸이동
			$i += 1;
		else if ($high < 0xE0)//128 <= code < 224 범위의 문자(확장 ASCII 문자)는 인덱스 2칸이동
			$i += 2;
		else if ($high < 0xF0)//224 <= code < 240 범위의 문자(유니코드 확장문자)는 인덱스 3칸이동 
			$i += 3;
		else//그외 4칸이동 (미래에 나올문자)
			$i += 4;
	}
	return $length;
}

/* 그냥 mb_strlen("한글문자열", "UTF-8") 쓰면 됨 */

/* utf8 문자 자르기 함수 */
function utf8_strcut($str, $chars, $tail = '...') {  
	if (utf8_length($str) <= $chars)//전체 길이를 불러올 수 있으면 tail을 제거한다.
		$tail = '';
	else
		$chars -= utf8_length($tail);//글자가 잘리게 생겼다면 tail 문자열의 길이만큼 본문을 빼준다.
	$len = strlen($str);
	for ($i = $adapted = 0; $i < $len; $adapted = $i) {
		$high = ord($str{$i});
		if ($high < 0x80)
			$i += 1;
		else if ($high < 0xE0)
			$i += 2;
		else if ($high < 0xF0)
			$i += 3;
		else
			$i += 4;
		if (--$chars < 0)
			break;
	}
	return trim(substr($str, 0, $adapted)) . $tail;
}

/*function sqlsrv_insert_id(){
	global $connect;
    $id = 0; 
    $chk = sqlsrv_query($connect,"SELECT @@identity AS id");
	if(sqlsrv_has_rows($chk) > 0){
		$row = sqlsrv_fetch_array($chk);
        $id = $row["id"];
    }
    return $id; 
}*/

/**
 * Get Thumbnail URL
 * 썸네일 주소를 가져옵니다. 기본값은 default 입니다.
 *
 * @param $url_or_id
 * @param $type
 * @return string
 */
function get_youtube_thumb( $url_or_id ){
	return '//img.youtube.com/vi/'.get_video_id( $url_or_id ).'/0.jpg';
}

function PM_TIME_KOR($time){
	switch($time){
		case "0": $result = "밤 12시"; break;
		case "15": $result = "밤 12시 15분"; break;
		case "30": $result = "밤 12시 30분"; break;
		case "45": $result = "밤 12시 45분"; break;
		case "100": $result = "오전 1시"; break;
		case "115": $result = "오전 1시 15분"; break;
		case "130": $result = "오전 1시 30분"; break;
		case "145": $result = "오전 1시 45분"; break;
		case "200": $result = "오전 2시"; break;
		case "215": $result = "오전 2시 15분"; break;
		case "230": $result = "오전 2시 30분"; break;
		case "245": $result = "오전 2시 45분"; break;
		case "300": $result = "오전 3시"; break;
		case "315": $result = "오전 3시 15분"; break;
		case "330": $result = "오전 3시 30분"; break;
		case "345": $result = "오전 3시 45분"; break;
		case "400": $result = "오전 4시"; break;
		case "415": $result = "오전 4시 15분"; break;
		case "430": $result = "오전 4시 30분"; break;
		case "445": $result = "오전 4시 45분"; break;
		case "500": $result = "오전 5시"; break;
		case "515": $result = "오전 5시 15분"; break;
		case "530": $result = "오전 5시 30분"; break;
		case "545": $result = "오전 5시 45분"; break;
		case "600": $result = "오전 6시"; break;
		case "615": $result = "오전 6시 15분"; break;
		case "630": $result = "오전 6시 30분"; break;
		case "645": $result = "오전 6시 45분"; break;
		case "700": $result = "오전 7시"; break;
		case "715": $result = "오전 7시 15분"; break;
		case "730": $result = "오전 7시 30분"; break;
		case "745": $result = "오전 7시 45분"; break;
		case "800": $result = "오전 8시"; break;
		case "815": $result = "오전 8시 15분"; break;
		case "830": $result = "오전 8시 30분"; break;
		case "845": $result = "오전 8시 45분"; break;
		case "900": $result = "오전 9시"; break;
		case "915": $result = "오전 9시 15분"; break;
		case "930": $result = "오전 9시 30분"; break;
		case "945": $result = "오전 9시 45분"; break;
		case "1000": $result = "오전 10시"; break;
		case "1015": $result = "오전 10시 15분"; break;
		case "1030": $result = "오전 10시 30분"; break;
		case "1045": $result = "오전 10시 45분"; break;
		case "1100": $result = "오전 11시"; break;
		case "1115": $result = "오전 11시 15분"; break;
		case "1130": $result = "오전 11시 30분"; break;
		case "1145": $result = "오전 11시 45분"; break;
		case "1200": $result = "낮 12시"; break;
		case "1215": $result = "낮 12시 15분"; break;
		case "1230": $result = "낮 12시 30분"; break;
		case "1245": $result = "낮 12시 45분"; break;
		case "1300": $result = "오후 1시"; break;
		case "1315": $result = "오후 1시 15분"; break;
		case "1330": $result = "오후 1시 30분"; break;
		case "1345": $result = "오후 1시 45분"; break;
		case "1400": $result = "오후 2시"; break;
		case "1415": $result = "오후 2시 15분"; break;
		case "1430": $result = "오후 2시 30분"; break;
		case "1445": $result = "오후 2시 45분"; break;
		case "1500": $result = "오후 3시"; break;
		case "1515": $result = "오후 3시 15분"; break;
		case "1530": $result = "오후 3시 30분"; break;
		case "1545": $result = "오후 3시 45분"; break;
		case "1600": $result = "오후 4시"; break;
		case "1615": $result = "오후 4시 15분"; break;
		case "1630": $result = "오후 4시 30분"; break;
		case "1645": $result = "오후 4시 45분"; break;
		case "1700": $result = "오후 5시"; break;
		case "1715": $result = "오후 5시 15분"; break;
		case "1730": $result = "오후 5시 30분"; break;
		case "1745": $result = "오후 5시 45분"; break;
		case "1800": $result = "오후 6시"; break;
		case "1815": $result = "오후 6시 15분"; break;
		case "1830": $result = "오후 6시 30분"; break;
		case "1845": $result = "오후 6시 45분"; break;
		case "1900": $result = "오후 7시"; break;
		case "1915": $result = "오후 7시 15분"; break;
		case "1930": $result = "오후 7시 30분"; break;
		case "1945": $result = "오후 7시 45분"; break;
		case "2000": $result = "오후 8시"; break;
		case "2015": $result = "오후 8시 15분"; break;
		case "2030": $result = "오후 8시 30분"; break;
		case "2045": $result = "오후 8시 45분"; break;
		case "2100": $result = "오후 9시"; break;
		case "2115": $result = "오후 9시 15분"; break;
		case "2130": $result = "오후 9시 30분"; break;
		case "2145": $result = "오후 9시 45분"; break;
		case "2200": $result = "오후 10시"; break;
		case "2215": $result = "오후 10시 15분"; break;
		case "2230": $result = "오후 10시 30분"; break;
		case "2245": $result = "오후 10시 45분"; break;
		case "2300": $result = "오후 11시"; break;
		case "2315": $result = "오후 11시 15분"; break;
		case "2330": $result = "오후 11시 30분"; break;
		case "2345": $result = "오후 11시 45분"; break;
		default: $result = "시간 설정 없음"; break;
	}
	return $result;
}

function br2nl($str) {
   $str = preg_replace("/(\r\n|\n|\r)/", "", $str);
   return preg_replace("=<br */?>=i", "\n", $str);
}

function time_diff($time) {
	$time_diff = mktime() - $time;

	$years = floor($time_diff / (86400 * 30 * 12));
	$time_diff -= $years * (86400 * 30 * 12);

	$months = floor($time_diff / (86400 * 30));
	$time_diff -= $months * (86400 * 30);

	$days = floor($time_diff / 86400);
	$time_diff -= $days * 86400;

	$hours = floor($time_diff / (60 * 60));
	$time_diff -= $hours * (60 * 60);

	$minutes = floor($time_diff / 60);
	$time_diff -= $minutes * 60;

	$seconds = floor($time_diff);

	$years_txt = "";
	$months_txt = "";
	$days_txt = "";
	$hours_txt = "";
	$minutes_txt = "";
	$seconds_txt = "";

	if($years != 0){
		$years_txt = $years . "년 ";
	}
	if($years == 0 && ($months != 0 || $years_txt != '')){
		$months_txt = $months . "달 ";
	}
	if(($years == 0 && $months == 0) && ($days != 0 || $months_txt != '')){
		$days_txt = $days . "일 ";
	}
	if(($years == 0 && $months == 0 && $days == 0) && ($hours != 0 || $days_txt != '')){
		$hours_txt = $hours . "시간 ";
	}
	if(($years == 0 && $months == 0 && $days == 0 && $hours == 0) && ($minutes != 0 || $hours_txt != '')){
		$minutes_txt = $minutes . "분 ";
	}
	if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0){
		$seconds_txt = floor($seconds/5)*5 . "초 ";
	}

	return $days_txt . $hours_txt . $minutes_txt . $seconds_txt . "전";
}

// 문자열을 파일로 저장
function String2File($sIn, $sFileOut) {
    $rc = false;

    do {
        if (!($f = fopen($sFileOut, "a+"))) {
            $rc = 1; break;
        }
        if (!fwrite($f, $sIn)) {
            $rc = 2; break;
        }
        $rc = true;
    } while (0);

	if ($f) {
        fclose($f);
    }
    return ($rc);
}

function mysqli_result($qry,$num){
	$row = mysqli_fetch_array($qry);
	return $row[$num];
}
?>