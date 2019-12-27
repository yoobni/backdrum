<?
	@header("Content-type: text/html; charset=utf-8");
	@header("P3P : CP=\"ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC\"");
	if(PMSYSTEM_CHECK != "!#DSS@#!SAADTUUF&&%&*") { PM_ERROR("허가되지 않는 접근입니다.","불법적인 SYSTEM 파일접근을 허가하지 않습니다."); exit; }

	$ua_raw = trim($_SERVER['HTTP_USER_AGENT']);
	$not_allowed = array('masscan/1.0','Java/1.6.0_41','Go-http-client/1.1','=Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.204 Safari/534.16','CATExplorador/1.0beta (sistemes at domini dot cat; http://domini.cat/catexplorador.html)','Mozilla/5.0 zgrab/0.x','Scanbot','sysscan/1.0 (https://github.com/robertdavidgraham/sysscan)','Wget(linux)','Wget/1.14 (linux-gnu)','python-requests/2.8.1');

	if(!$ua_raw || in_array($ua_raw,$not_allowed)) {
		echo "error";
		exit;
	}

	// 커스텀함수로드
	require_once ("function.php");

	// 유저에이전트체크함수로드
	if($user_show == 1){
		include($_SERVER['DOCUMENT_ROOT']."/includes/module/Mobile-Detect-2.8.11/Mobile_Detect.php");
		$ua_detect = new Mobile_Detect;
	}

	// DB연결시도
	@mysqli_close($connect);
	$connect = PM_DBCONNECT();

	// 도메인 정보 세팅
	$urlb = $_SERVER["HTTP_HOST"]; 
	$sub_domain = explode(".",$urlb); 
	for($i=0;$i<count($sub_domain);$i++) {
		if($sub_domain[$i] != 'www' && $sub_domain[$i] != 'com' && $sub_domain[$i] != 'net' && $sub_domain[$i] != 'kr' && !($sub_domain[$i] == 'co' && $sub_domain[$i+1] == 'kr')) {
			$aurl .= "."; 
			$aurl .= $sub_domain[$i];
		}
	}
	$raw_url = explode(".",$aurl);
	$raw_url = $raw_url[count($raw_url) - 1];
	if($sub_domain[count($sub_domain)-1] == 'com'){
		$end_url = 'com';
	} else if($sub_domain[count($sub_domain)-1] == 'net'){
		$end_url = 'net';
	} else if($sub_domain[count($sub_domain)-1] == 'kr' && $sub_domain[count($sub_domain)-2] != 'co'){
		$end_url = 'kr';
	} else if($sub_domain[count($sub_domain)-2] == 'co' && $sub_domain[count($sub_domain)-1] == 'kr'){
		$end_url = 'co.kr';
		echo "<script>location.href='http://".$raw_url.".net'</script>";
		exit;
	}

	// 서버설정 셋팅
	$PMSYSTEM['Path'] = "D:/82. Backdrum";
	$PMSYSTEM['Session_Path'] = "sess";
	$PMSYSTEM['MAIN'] = "/index.php";
	$PMSYSTEM['URL_PATH'] = "/";
	$PMSYSTEM["HTTP_REFERER"] = str_replace("www.","",strtolower($_SERVER["HTTP_REFERER"]));
	$PMSYSTEM["SETTING_DOMAIN"] = 'backdrum.net';

	// 기본유저 정보 수집
	$USERX['IP'] = ip2long($_SERVER['REMOTE_ADDR']);
	$USERX['PHP_SELF'] = $_SERVER['PHP_SELF'];

	// 키워드 리스트 관련 항목 세팅
	if(!$_REQUEST['page']) { $PMLIST['PAGE'] = 1; } else { $PMLIST['PAGE'] = intval($_REQUEST['page']); }
	$PMLIST['INC'] = $_REQUEST['inc']?PM_DELHTML($_REQUEST['inc']):'main';
	$PMLIST['BOARD'] = ($_REQUEST['board'])?PM_DELHTML($_REQUEST['board']):"all";
	$PMLIST['TYPE'] = ($_REQUEST['type'])?PM_DELHTML($_REQUEST['type']):"all";
	$PMLIST['FILTER'] = PM_DELHTML($_REQUEST['filter']);
	$PMLIST['CATEGORY'] = PM_DELHTML($_REQUEST['category']);
	if($PMLIST['INC'] == "list" && !$PMLIST['CATEGORY']) $PMLIST['CATEGORY'] = 'all';
	$PMLIST['NUM'] = intval($_REQUEST['num']);
	$PMLIST['IDX'] = intval($_REQUEST['idx']);
	$PMLIST['IDX2'] = intval($_REQUEST['idx2']);
	$PMLIST['SHOP_IDX'] = intval($_REQUEST['shop_idx']);
	$PMLIST['DATE'] = PM_DELHTML($_REQUEST['date']);
	$PMLIST['PROC'] = PM_DELHTML($_REQUEST['proc']);
	$PMLIST['CODE'] = PM_DELHTML($_REQUEST['code']);
	$PMLIST['TITLE'] = PM_DELHTML($_REQUEST['title']);
	$PMLIST['TEXT'] = PM_DELHTML($_REQUEST['text']);
	$PMLIST['ORDER'] = PM_DELHTML($_REQUEST['order']);
	$PMLIST['MODE'] = PM_DELHTML($_REQUEST['mode']);
	$PMLIST['SEARCH'] = PM_DELHTML($_REQUEST['search']);
	$PMLIST['PLACE'] = PM_DELHTML($_REQUEST['place']);
	$PMLIST['PLACE1'] = intval($_REQUEST['place1']);
	$PMLIST['PLACE2'] = intval($_REQUEST['place2']);
	$PMLIST['KEYLOG'] = PM_DELHTML($_REQUEST['keylog']);
	$PMLIST['ARTICLE_IDX'] = intval($_REQUEST['article_idx']);

	if($PMLIST['NO'] == 0 && $PMLIST['PROC'] == "view") { $PMLIST['PROC'] = "list"; }

	// 유저 레벨 설정
	$ULEVEL[0] = '이등병';
	$ULEVEL[1] = '이등병';
	$ULEVEL[2] = '일병';
	$ULEVEL[3] = '상병';
	$ULEVEL[4] = '병장';
	$ULEVEL[5] = '하사';
	$ULEVEL[6] = '중사';
	$ULEVEL[7] = '상사';
	$ULEVEL[8] = '원사';
	$ULEVEL[9] = '준위';
	$ULEVEL[10] = '소위';
	$ULEVEL[11] = '중위';
	$ULEVEL[12] = '대위';
	$ULEVEL[13] = '소령';
	$ULEVEL[14] = '중령';
	$ULEVEL[15] = '대령';
	$ULEVEL[16] = '준장';
	$ULEVEL[17] = '소장';
	$ULEVEL[18] = '중장';
	$ULEVEL[19] = '대장';
	$ULEVEL[20] = '원수';
	$ULEVEL[99] = '운영자';

	// 기본설정
	$page = $PMLIST['PAGE'];
	if($PMLIST['SEL_Y'] == 0) { $PMLIST['SEL_Y'] = date("Y"); }
	if($PMLIST['SEL_M'] == 0) { $PMLIST['SEL_M'] = date("m"); }

	// DB 내 세팅
	$PMSETTING = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM settings LIMIT 1"));

	// 세션설정 (세션은 3일동안 유효하게 설정)
	if(!is_dir($PMSYSTEM['Path']."/".$PMSYSTEM['Session_Path'])) {
		@mkdir($PMSYSTEM['Path']."/".$PMSYSTEM['Session_Path'], 0777);
		@chmod($PMSYSTEM['Path']."/".$PMSYSTEM['Session_Path'], 0777);
	}

	// 서브도메인 로그인 인식
	//ini_set("session.cookie_domain", ".".$raw_url.".".$end_url);
	ini_set("session.cookie_domain", $_SERVER['HTTP_HOST']);

	// 로그인확인 & 접속 갱신
	$session_name = 'PMLIST_SESS';
	@session_name( $session_name );
	@session_save_path( $PMSYSTEM['Path']."/".$PMSYSTEM['Session_Path'] );

	session_set_cookie_params(0, "/"); 
	session_cache_limiter('no-cache, must-revalidate'); 

	if( version_compare( PHP_VERSION, '5.1.2', 'lt' ) && isset( $_COOKIE[$session_name] ) && eregi( "\r|\n", $_COOKIE[$session_name] ) ) {
		die('DB CONNECT ERROR');
	}
	@session_start();

	$PMSYSTEM['SESS'] = session_id();

	// 회원정보가져오기
	if(isset($_SESSION['mem_idx'])){
		$MEM = PM_MEMBER();
		switch($MEM['level']){
			case '1': $MEM['user_level'] = '이등병';
				break;
			case '2': $MEM['user_level'] = '일병';
				break;
			case '3': $MEM['user_level'] = '상병';
				break;
			case '4': $MEM['user_level'] = '병장';
				break;
			case '5': $MEM['user_level'] = '하사';
				break;
			case '6': $MEM['user_level'] = '중사';
				break;
			case '7': $MEM['user_level'] = '상사';
				break;
			case '8': $MEM['user_level'] = '원사';
				break;
			case '9': $MEM['user_level'] = '준위';
				break;
			case '10': $MEM['user_level'] = '소위';
				break;
			case '11': $MEM['user_level'] = '중위';
				break;
			case '12': $MEM['user_level'] = '대위';
				break;
			case '13': $MEM['user_level'] = '소령';
				break;
			case '14': $MEM['user_level'] = '중령';
				break;
			case '15': $MEM['user_level'] = '대령';
				break;
			case '16': $MEM['user_level'] = '준장';
				break;
			case '17': $MEM['user_level'] = '소장';
				break;
			case '18': $MEM['user_level'] = '중장';
				break;
			case '19': $MEM['user_level'] = '대장';
				break;
			case '20': $MEM['user_level'] = '원수';
				break;
			default : $MEM['user_level'] = '이등병';
				break;
		}
	} else {
		$MEM['level'] = 0;
		if($_SESSION["mobile"]) $MEM['Mobile'] = $_SESSION['mobile'];
	}

	// 카테고리 정보 가져오기
	$PMCATE['all'] = '전체';
	$category_qry = mysqli_query($connect, "SELECT * FROM category WHERE `show` = '0'");
	if(mysqli_num_rows($category_qry) > 0){
		while($row = mysqli_fetch_array($category_qry)){
			$PMCATE[$row['code']] = $row['title'];
		}
	}

	$UA = PM_GETBROWSER();

	if($_REQUEST['inc'] || $_SERVER['REQUEST_URI'] == '/'){
		// UA 저장
		mysqli_query($connect,"INSERT INTO user_agent SET ip = '".$_SERVER['REMOTE_ADDR']."', ua = '".$_SERVER['HTTP_USER_AGENT']."'");

		// IP 카운트
		mysqli_query($connect,"INSERT INTO statistics SET ip = '".$_SERVER['REMOTE_ADDR']."', regdate = '".date("Y-m-d")."', lasttime = NOW() ON DUPLICATE KEY UPDATE count = count + 1, lasttime = NOW()");
	}
?>