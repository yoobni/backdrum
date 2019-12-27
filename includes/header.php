<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko">
<head>
<?if($PMLIST['INC'] == 'article' && $PMLIST['IDX']){
	$board_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y-%m-%d') AS regdate FROM board WHERE idx = '".$PMLIST['IDX']."' AND type = '0' AND status = '0'");
	if(mysqli_num_rows($board_qry) > 0){
		$board_info = mysqli_fetch_array($board_qry);
		$category_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$board_info['category_idx']."'"));
		$writer_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM users WHERE idx = '".$board_info['user_idx']."'"));
	}
}?>
<?if($sub_domain[0] != 'www' && $sub_domain[0] != 'backdrum'){
	$page_qry = mysqli_query($connect,"SELECT * FROM facebook_page WHERE code = '".$sub_domain[0]."'");
	if(mysqli_num_rows($page_qry) > 0){
		$page_info = mysqli_fetch_array($page_qry);?>
<meta property="fb:pages" content="<?=$page_info['page_id']?>" />
	<?}
}?>
<meta name="viewport" content="width=1100, user-scalable=no, target-densitydpi=medium-dpi">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="robots" content="index, follow" />
<meta name="robots" content="imageindex" />
<meta name="robots" content="archive" />
<meta name="googlebot" content="index, follow" />
<meta name="googlebot" content="imageindex" />
<?if($board_info){?>
<meta name="Keywords" content="<?=$board_info['tag']?>,<?=$category_info['title']?>,뒷북,백드럼,backdrum,뒷드럼,뒷drum,백drum,back드럼,back북,매니아,커뮤니티,최신글,재밌는글" />
<title><?if(strpos($_SERVER['HTTP_USER_AGENT'],'facebookexternalhit') === false){?>뒷북 > <?=$category_info['title']?> > <?}?><?=$board_info['title']?></title>
<?} else {?>
<meta name="Keywords" content="뒷북,백드럼,backdrum,뒷드럼,뒷drum,백drum,back드럼,back북,매니아,커뮤니티,최신글,재밌는글" />
<title>뒷북 :: 뒷북치는 커뮤니티!</title>
<?}?>

<!-- 구 버전의 인터넷 익스플로러에서 HTML5 태그를 인식하게 합니다. -->
<!--[if lte IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NSZRKPT');</script>
<!-- End Google Tag Manager -->
<script src="/js/jquery-1.11.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/php.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/jquery_plugin/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script type="text/javascript" src="/js/jquery_plugin/wSelect/wSelect.min.js"></script>
<script src="/js/placeholders.min.js"></script>
<script src="/js/common.js" charset="utf-8"></script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2593809133867899",
    enable_page_level_ads: true
  });
</script>

<link rel="stylesheet" type="text/css" href="/css/content.css" />
<link rel="Stylesheet" type="text/css" href="/css/wSelect.css" />
</head>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NSZRKPT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<body>
	<div id="mask"></div>
	<div class="top_outer">
		<div class="top_line">
			<div class="wrap">
				<a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.backdrum.co.kr')" href="javascript:">뒷북을 시작페이지로</a> | <a href="javascript:bookmarksite('뒷북 :: 뒷북치는 커뮤니티!', 'http://www.backdrum.co.kr')">즐겨찾기 추가</a>
			</div>
		</div>
		<div class="wrap">
			<div class="top">
				<div>
					<a href="/" class="logo"><img src="/images/logo.png" alt="" /></a>
				</div>
				<div class="top_r">
				<?foreach($PMCATE as $key => $val){?>
					<?if($key == 'all'){?><div id="cate_<?=$key?>"class="category<?if($PMLIST['INC'] == 'list' && $PMLIST['CATEGORY'] == $key){?> on<?}?>"<?if($PMLIST['INC'] != 'list'){?> onclick="location.href='/?inc=list'"<?}?>>전체기사</div><?} else {?><div id="cate_<?=$key?>"class="category<?if($PMLIST['INC'] == 'list' && $PMLIST['CATEGORY'] == $key){?> on<?}?>"<?if($PMLIST['INC'] != 'list'){?> onclick="location.href='/?inc=list&category=<?=$key?>'"<?}?>><?=$val?></div><?}?>
				<?}?>
				</div>
			</div>
		</div>
	</div>
	<div class="wrap">
		<div class="layout1">
			<div class="layout2">
