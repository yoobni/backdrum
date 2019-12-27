<div class="layout2_r">
	<!--div id="sky">
		<a target="_blank" href="http://click.linkprice.com/click.php?m=replicas1&a=A100528923&l=0031&u_id="><img src="http://replic.godohosting.com/linkprice/home/160×600.jpg" border="0" width="160" height="600"></a><img src="http://track.linkprice.com/lpshow.php?m_id=replicas1&a_id=A100528923&p_id=0000&l_id=0031&l_cd1=2&l_cd2=0" width="1" height="1" border="0" nosave style="display:none">
	</div-->
	<div class="rank">
		<div class="rank_title">인기 게시물</div>
		<ol class="rank_list">
		<?$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0' ORDER BY regdate DESC, view_count DESC LIMIT 10");
		if(mysqli_num_rows($board_qry) > 0){
			$i = 0;
			while($bdata = mysqli_fetch_array($board_qry)){?>
			<li class="<?if($i == 0){?>rart<?} else {?>art_txt_title<?}?>">
				<a href='/?inc=article&idx=<?=$bdata['idx']?>'>
				<?if($i == 0){
					$img_exp = explode(',',$bdata['image']);
					if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
						$image_url = $img_exp[0];
					} else {
						$image_url = "/uploaded/board/".$img_exp[0];
					}?>
					<div class="thumb" style="background-image:url(<?=$image_url?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>', sizingMethod='scale');"></div>
					<div class="art_title">
						<p><?=$bdata['title']?></p>
					</div>
				<?} else {?><?=$bdata['title']?><?}?>
				</a>
			</li>
			<?$i++;}
		}?>
		</ol>
	</div>
</div>