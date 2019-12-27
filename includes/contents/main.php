<div class="layout2_l">
	<div class="top_menu">
		<span class="top_menu_link">
		<?foreach($PMCATE as $key => $val){?>
			<?if($key == 'all'){?><div code="<?=$key?>" class="on">전체기사</div><?} else {?><div code="<?=$key?>"><?=$val?></div><?}?>
		<?}?>
		</span>
	</div>
	<div class="articles_main_left"></div>
</div>
<script>
var summary = null;
var summary_now = 0;
var summary_delay = 5000;
$(document).ready(function(){
	$('.layout2_l').hover(function(){
		clearTimeout(summary);
	}, function(){
		summary = setTimeout(function(){
			summary_timing();
		}, summary_delay);
	});
	$('.top_menu_link div').mouseover(function(){
		summary_now = $('.top_menu_link div').index($(this));
		summary_mouseover();
	});
	summary_mouseover();
	summary = setTimeout(function(){
		summary_timing();
	}, summary_delay);
});

function summary_mouseover(){
	$('.top_menu_link div').removeClass('on');
	$('.top_menu_link div:eq('+summary_now+')').addClass('on');
	$.post('/includes/ajax_loads/main.php',{'time':mktime(),'proc':'summary','category':$('.top_menu_link div:eq('+summary_now+')').attr('code')},function(data){
		$('.articles_main_left').html(data);
	});
}

function summary_timing(){
	summary_now++;
	if(summary_now >= $('.top_menu_link div').length) summary_now = 0;
	summary_mouseover();
	summary = setTimeout(function(){
		summary_timing();
	}, summary_delay);
}
</script>
<?include($_SERVER['DOCUMENT_ROOT'].'/includes/contents/right_contents.php');?>
<div style="clear:both;"></div>
<div class="layout2_d">
	<div class="l2d">
		<div class="l_title">전체기사</div>
		<ol class="l2_list" thumb_idx="1">
			<a class="lart">
				<div class="thumb" thumb_idx="1"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' ORDER BY regdate DESC, view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">유머관련</div>
		<ol class="l2_list" thumb_idx="2">
			<a class="lart">
				<div class="thumb" thumb_idx="2"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%1%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">연애관련</div>
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-layout="in-article"
     data-ad-format="fluid"
     data-ad-client="ca-pub-8399890973546855"
     data-ad-slot="6977240731"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
		<ol class="l2_list" thumb_idx="3">
			<a class="lart">
				<div class="thumb" thumb_idx="3"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%2%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">게임관련</div>
		<ol class="l2_list" thumb_idx="4">
			<a class="lart">
				<div class="thumb" thumb_idx="4"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%3%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
</div>
<div class="layout2_d">
	<div class="l2d">
		<div class="l_title">사건사고</div>
		<ol class="l2_list" thumb_idx="5">
			<a class="lart">
				<div class="thumb" thumb_idx="5"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%4%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">애완동물</div>
		<ol class="l2_list" thumb_idx="6">
			<a class="lart">
				<div class="thumb" thumb_idx="6"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_title LIKE '%애완동물%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">여자관련</div>
		<ol class="l2_list" thumb_idx="7">
			<a class="lart">
				<div class="thumb" thumb_idx="7"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%6%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
	<div class="l2d">
		<div class="l_title">기타등등</div>
		<ol class="l2_list" thumb_idx="8">
			<a class="lart">
				<div class="thumb" thumb_idx="8"></div>
			</a>
			<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' AND category_idx LIKE '%7%' ORDER BY view_count DESC LIMIT 5");
			if(mysqli_num_rows($board_qry) > 0){ while($bdata = mysqli_fetch_array($board_qry)){
				$img_exp = explode(',',$bdata['image']);
				if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
					$image_url = $img_exp[0];
				} else {
					$image_url = "/uploaded/board/".$img_exp[0];
				}?>
			<li class="lart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
			<?}}?>
		</ol>
	</div>
</div>
<script>
$(document).ready(function(){
	$('.l2_list').each(function(){
		var obj = this;
		$(obj).find('.lart_txt_title').mouseover(function(){
			$(obj).find('.lart_txt_title').removeClass('on');
			$(this).addClass('on');

			var thumb_idx = $(this).parent().attr('thumb_idx');
			var thumb = $(this).attr('thumb');
			$('.thumb[thumb_idx='+thumb_idx+']').css({'background-image':'url('+thumb+')','filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(src="+thumb+",sizingMethod='scale')",'-ms-filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+thumb+"', sizingMethod='scale')"}).parent().attr('href',$(this).find('a').attr('href'));
		});
		$(this).find('li:eq(0)').mouseover();
	});
});
</script>
