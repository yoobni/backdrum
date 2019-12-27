<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/function.php');
include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

$PMLIST['TYPE'] = PM_DELHTML($_REQUEST['type']);

if($PMLIST['PROC'] == 'summary'){
	$order_by = " ORDER BY regdate DESC, view_count DESC";
	$where_add = "";
	if($PMLIST['CATEGORY'] != 'all'){
		$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE code = '".$PMLIST['CATEGORY']."'");
		if(mysqli_num_rows($category_qry) > 0){
			$category_info = mysqli_fetch_array($category_qry);
		} else {
			echo "잘못된 카테고리입니다.";
			exit;
		}
		$where_add = " AND category_idx = ".$category_info['idx'];
	}
	$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0'".$where_add.$order_by." LIMIT 7");
	if(mysqli_num_rows($board_qry) > 0){
		$i = 0;
		while($bdata = mysqli_fetch_array($board_qry)){
			$img_exp = explode(',',$bdata['image']);
			if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
				$image_url = $img_exp[0];
			} else {
				$image_url = "/uploaded/board/".$img_exp[0];
			}
			if($i == 0){?>
		<a href='/?inc=article&idx=<?=$bdata['idx']?>'>
			<div class="top_article">
				<div class="thumb" style="background-image:url(<?=$image_url?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>', sizingMethod='scale');"></div>
				<div class="top_article_title">
					<p><?=$bdata['title']?></p>
					<span><font><?=$bdata['user_nick']?></font>님이 작성</span>
				</div>
			</div>
		</a>
		<div class="right_article">
			<?} else if($i <= 2){?>
			<a href='/?inc=article&idx=<?=$bdata['idx']?>'>
				<div class="rart"<?if($i == 2){?> style="margin-top:0.8rem;"<?}?>>
					<div class="thumb" style="background-image:url(<?=$image_url?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>', sizingMethod='scale');"></div>
					<div class="art_title">
						<p><?=$bdata['title']?></p>
					</div>
				</div>
			</a>
			<?} else {
				if($i == 3){?>
		</div>
		<div class="middle_articles">
			<div class="devide"></div>
				<?}?>
			<a href='/?inc=article&idx=<?=$bdata['idx']?>' class="mart">
				<div class="thumb" style="background-image:url(<?=$image_url?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>', sizingMethod='scale');"></div>
				<div class="art_title">
					<p><?=$bdata['title']?></p>
				</div>
			</a>
			<?}
			$i++;
		}?>
		</div><?} else {?><a href="/?inc=list">등록된 게시물이 없어요!</a><?}
}?>
