<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

include('../system/function.php');
include('../system/system.php');

header('P3P: CP="CAO PSA CONi OTR OUR DEM ONL"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Pragma: no-cache");
header("Cache-Control: no-store,no-cache,must-revalidate");
header('Cache-Control: post-check=0, pre-check=0', FALSE);

$PMLIST['CIDX'] = intval($_REQUEST['cidx']);

if($PMLIST['PROC'] == 'list'){?>
			<table class="table1 list<?if($PMLIST['TYPE'] != 'best'){?> table_top<?}?>">
				<colgroup>
					<col width="168">
					<?if($PMLIST['CATEGORY'] == 'all'){?><col width="100"><?}?>
					<col width="*">
					<col width="55">
					<col width="62">
					<col width="55">
					<col width="85">
				</colgroup>
				<!--thead>
					<tr>
						<th><span>번호</span></th>
						<?if($PMLIST['CATEGORY'] == 'all'){?><th><span>분류</span></th><?}?>
						<th><span>제목</span></th>
						<th><span>이름</span></th>
						<th><span>날짜</span></th>
						<th><span>조회</span></th>
						<th><span>추천</span></th>
					</tr>
				</thead-->
				<tbody>
					<!--tr class="item">
						<th colspan="<?if($PMLIST['CATEGORY'] == 'all'){?>7<?}else{?>6<?}?>">
							<img src="/images/item1.jpg" alt="" />
							<img src="/images/item2.jpg" alt="" />
							<a href="<?=$PMSETTING['ad_url']?>" class="adlist" target="_blank"><?=$PMSETTING['ad_title']?></a>
						</th>
					</tr-->
					<?$notice_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y.%m.%d') cdate FROM board WHERE category_idx = 21 AND status = '0' ORDER BY idx DESC LIMIT 2");
					if(mysqli_num_rows($notice_qry) > 0){
						while($nrow = mysqli_fetch_array($notice_qry)){?>
					<tr class="notice">
						<td><b>[공지]</b></td>
						<th<?if($PMLIST['CATEGORY'] == 'all'){?> colspan="2"<?}?>><a href="/?inc=article&idx=<?=$nrow['idx']?>&category=<?=$PMLIST['CATEGORY']?>"<?if($PMLIST['CATEGORY'] != 'all'){?> class="onlist"<?}?>><?=$nrow['title']?></a></th>
						<td><?=$nrow['user_nick']?></td>
						<td><?=$nrow['cdate']?></td>
						<td><?=number_format($nrow['view_count'])?></td>
						<td><?=number_format($nrow['recommend_count'])?></td>
					</tr>
						<?}
					}?>
	<?if($PMLIST['SEARCH'] && $PMLIST['MODE']){
		$search_kwd = str_replace('%','\%',$PMLIST['SEARCH']);
		function stripslashes_deep($var){
		    $var = is_array($var)?
		                  array_map('stripslashes_deep', $var) :
		                  stripslashes($var);

		    return $var;
		}
		function mysql_real_escape_string_deep($var){
		    $var = is_array($var)?
		                  array_map('mysql_real_escape_string_deep', $var) :
		                  mysql_real_escape_string($var);

		    return $var;
		}
		if(!get_magic_quotes_gpc()) {
		  $search_kwd = addslashes($search_kwd);
		}

		if(preg_match('/[^A-Za-z0-9]/', $search_kwd)) {
		  exit ("알파벳과 한글 그리고 숫자만 입력이 가능합니다");
		}
		if($PMLIST['MODE'] == 'title'){
			$search_add = " AND title LIKE '%".$search_kwd."%'";
		} else if($PMLIST['MODE'] == 'writer'){
			$search_add = " AND (user_name LIKE '%".$search_kwd."%' OR user_nick LIKE '%".$search_kwd."%')";
		} else if($PMLIST['MODE'] == 'content'){
			$search_add = " AND content LIKE '%".$search_kwd."%'";
		}
	}

	if($PMLIST['CATEGORY'] == 'all'){
		$cate_chk = true;
	} else {
		$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE code = '".$PMLIST['CATEGORY']."'");
		if(mysqli_num_rows($category_qry) > 0){
			$cate_chk = true;
			$category_info = mysqli_fetch_array($category_qry);
			$search_add .= " AND category_idx LIKE '%".$category_info['idx']."%'";
		} else {
			$cate_chk = false;
		}
	}

	if($cate_chk){
		$all_nums = mysqli_fetch_array(mysqli_query($connect,"SELECT COUNT(idx) FROM board WHERE status ='0'".$search_add));
		if(($all_nums = $all_nums[0]) == 0){?>
		<tr>
			<td colspan="<?if($PMLIST['CATEGORY'] == 'all'){?>7<?}else{?>6<?}?>" style="height:57px;">게시물이 없습니다.</td>
		</tr>
		<?} else {
			$view_count = $PMLIST['TYPE'] == 'best'?10:20;
			$all_pages = ceil($all_nums/$view_count);
			if($PMLIST['PAGE'] > $all_pages) $PMLIST['PAGE'] = $all_pages;
			$start_num = ($PMLIST['PAGE']-1)*$view_count;

			$board_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y.%m.%d') cdate FROM board WHERE status ='0'".$search_add." ORDER BY idx DESC LIMIT ".$start_num.",".$view_count);

			if(($result_count = mysqli_num_rows($board_qry)) > 0){
				$i=0;
				while($bdata = mysqli_fetch_array($board_qry)){
					if($PMLIST['CATEGORY'] == 'all') $cate_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$bdata['category_idx']."'"));
					$img_exp = explode(',',$bdata['image']);
					if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
						$image_url = $img_exp[0];
					} else if(!$img_exp[0]){
						$image_url = '/images/search_img.png';
					} else {
						$image_url = "/uploaded/board/".$img_exp[0];
					}?>
					<tr class="list_new" onclick=location.href="/?inc=article&idx=<?=$bdata['idx']?>&category=<?=$PMLIST['CATEGORY']?>">
						<!--td><b><?=$all_nums - $start_num - $i?></b></td>
						<?if($PMLIST['CATEGORY'] == 'all'){?><td><?=$cate_info['title']?></td><?}?>
						<th>
							<a href="/?inc=article&idx=<?=$bdata['idx']?>"<?if($PMLIST['CATEGORY'] != 'all'){?> class="onlist"<?}?>><?=$bdata['title']?><?if($bdata['comment_count'] > 0){?><font style="font-weight:600; color:#d90000;">(<?=number_format($bdata['comment_count'])?>)</font><?}?></a>
							<?if($bdata['cdate'] == date("Y.m.d")){?><img src="/images/icon1.png" alt="" /><?}?>
							<?if($bdata['image_use'] == '1'){?><img src="/images/icon2.png" alt="" /><?}?>
						</th>
						<td><?=$bdata['user_nick']?$bdata['user_nick']:$bdata['user_name']?></td>
						<td><?=$bdata['cdate']?></td>
						<td><?=number_format($bdata['view_count'])?></td>
						<td><?=number_format($bdata['recommend_count'])?></td-->
						<td class="list_thumb" style="background-image:url('<?=$image_url?>');"></td>
						<td class="list_texts" colspan="<?if($PMLIST['CATEGORY'] == 'all'){?>5<?}else{?>4<?}?>">
							<div class="list_text1"><div class="list_title_new"><?=$bdata['title']?><?if($bdata['comment_count'] > 0){?><font style="font-weight:600; color:#d90000;">(<?=number_format($bdata['comment_count'])?>)</font><?}?></div></div>
							<div class="list_text2"><div class="list_thumb_new"></div><?=$bdata['user_nick']?$bdata['user_nick']:$bdata['user_name']?></div>
						</td>
						<td><?=$bdata['cdate']?></td>
					</tr>
				<?$i++;}
			}
		}?>
				</tbody>
			</table>
		<?if($all_nums > 0){?>
			<div class="paging">
				<?$start_decades_page = floor(($PMLIST['PAGE']-1)/10)*10+1;
				$end_decades_page = ($start_decades_page+9 > $all_pages)?$all_pages:$start_decades_page+9;?>
				<?if($PMLIST['PAGE'] != 1){?><a href="javascript:" page="1"><img src="/images/first.jpg" alt="" /></a><?}?><?if($PMLIST['PAGE'] != 1){?><a href="javascript:" page="<?=$PMLIST['PAGE'] - 1?>"><img src="/images/prev.jpg" alt="" /></a><?}?><?for($i=$start_decades_page;$i<=$end_decades_page;$i++){?><a href="javascript:" page="<?=$i?>"<?if($PMLIST['PAGE'] == $i){?> class="on"<?}?>><?=$i?></a><?}?><?if($PMLIST['PAGE'] != $all_pages){?><a href="javascript:" page="<?=$PMLIST['PAGE'] + 1?>"><img src="/images/next.jpg" alt="" /></a><?}?><?if($PMLIST['PAGE'] != $all_pages){?><a href="javascript:" page="<?=$all_pages?>"><img src="/images/last.jpg" alt="" /></a><?}?>
			</div>
		<?}?>
	<?} else {?>
	잘못된 게시판으로 접근하셨습니다.
	<?}?>||^||<?if($PMLIST['CATEGORY'] == 'all'){?>전체<?} else {?><?=$category_info['title']?><?}
} elseif($PMLIST['PROC'] == 'search'){
	mysqli_query($connect,"INSERT INTO search_kwd SET kwd = '".$PMLIST['SEARCH']."', count = 1, regdate = NOW() ON DUPLICATE KEY UPDATE count = count + 1");
	$search_kwd = str_replace('%','\%',$PMLIST['SEARCH']);
	if(!$PMLIST['MODE']) $PMLIST['MODE'] = 'title';
	if($PMLIST['MODE'] == 'title'){
		$search_add = " AND title LIKE '%".$search_kwd."%'";
	} else if($PMLIST['MODE'] == 'writer'){
		$search_add = " AND (user_name LIKE '%".$search_kwd."%' OR user_nick LIKE '%".$search_kwd."%')";
	} else if($PMLIST['MODE'] == 'content'){
		$search_add = " AND content LIKE '%".$search_kwd."%'";
	}

	$all_nums = mysqli_fetch_array(mysqli_query($connect,"SELECT COUNT(idx) FROM board WHERE status ='0'".$search_add));
	if(($all_nums = $all_nums[0]) == 0){?>
	<div class="search_result_box search_result_box2">
		<dt>
			검색결과가 없습니다.
		</dt>
	</div>
	<?} else {
		$view_count = 10;
		$all_nums = $all_nums[0]; $all_pages = ceil($all_nums/$view_count);
		if($PMLIST['PAGE'] > $all_pages) $PMLIST['PAGE'] = $all_pages;
		$start_num = ($PMLIST['PAGE']-1)*$view_count;

		$board_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y.%m.%d') cdate FROM board WHERE status ='0'".$search_add." ORDER BY idx DESC LIMIT ".$start_num.",".$view_count);

		if(mysqli_num_rows($board_qry) > 0){
			$i=0;
			while($bdata = mysqli_fetch_array($board_qry)){
				$cate_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$bdata['category_idx']."'"));?>
			<div class="search_result_box search_result_box2">
				<dt>
				<?if($bdata['image_use'] == '1'){
					$img_exp = explode(',',$bdata['image']);
					if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
						$image_url = $img_exp[0];
					} else if(!$img_exp[0]){
						$image_url = '/images/search_img.png';
					} else {
						$image_url = "/uploaded/board/".$img_exp[0];
					}?>
					<dl><!-- 이미지사이즈 76*76 --><div class="thumb" style="background-image:url(<?=$image_url?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$image_url?>', sizingMethod='scale');"></div></dl>
				<?}?>
					<a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=str_replace($PMLIST['SEARCH'], "<b>".$PMLIST['SEARCH']."</b>", $bdata['title'])?></a><strong><?=$bdata['cdate']?></strong>
					<p><?=trim(strip_tags($bdata['content']))?></p>
					<span>
						[카테고리] : <b><?=$cate_info['title']?></b> | [닉네임] : <b><?=$bdata['user_nick']?$bdata['user_nick']:$bdata['user_name']?></b> | 조회 <?=number_format($bdata['view_count'])?> | 추천 <?=number_format($bdata['recommend_count'])?>
					</span>
				</dt>
			</div>
			<?$i++;}
		}
	}?>||^||
	<?if($all_nums > 0){?>
		<?$start_decades_page = floor(($PMLIST['PAGE']-1)/10)*10+1;
		$end_decades_page = ($start_decades_page+9 > $all_pages)?$all_pages:$start_decades_page+9;?>
		<?if($PMLIST['PAGE'] != 1){?><a href="javascript:" page="1"><img src="/images/first.jpg" alt="" /></a><?}?><?if($PMLIST['PAGE'] != 1){?><a href="javascript:" page="<?=$PMLIST['PAGE'] - 1?>"><img src="/images/prev.jpg" alt="" /></a><?}?><?for($i=$start_decades_page;$i<=$end_decades_page;$i++){?><a href="javascript:" page="<?=$i?>"<?if($PMLIST['PAGE'] == $i){?> class="on"<?}?>><?=$i?></a><?}?><?if($PMLIST['PAGE'] != $all_pages){?><a href="javascript:" page="<?=$PMLIST['PAGE'] + 1?>"><img src="/images/next.jpg" alt="" /></a><?}?><?if($PMLIST['PAGE'] != $all_pages){?><a href="javascript:" page="<?=$all_pages?>"><img src="/images/last.jpg" alt="" /></a><?}?>
	<?}
} else if($PMLIST['PROC'] == 'cate_best'){
	$search_add = "";
	if($PMLIST['CATEGORY'] != 'all'){
		$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE code = '".$PMLIST['CATEGORY']."'");
		if(mysqli_num_rows($category_qry) > 0) {
			$category_info = mysqli_fetch_array($category_qry);
			$search_add .= " AND category_idx LIKE '%".$category_info['idx']."%'";
		} else {
			echo "잘못된 카테고리입니다.";
		}
	}
	$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE status = '0'".$search_add." ORDER BY view_count DESC LIMIT 5");
	if(mysqli_num_rows($board_qry) > 0){
		while($bdata = mysqli_fetch_array($board_qry)){
			$img_exp = explode(',',$bdata['image']);
			if(strpos($img_exp[0], "http://") !== false || strpos($img_exp[0], "https://") !== false){
				$image_url = $img_exp[0];
			} else {
				$image_url = "/uploaded/board/".$img_exp[0];
			}?>
			<li class="bart_txt_title" thumb="<?=$image_url?>"><a href="/?inc=article&idx=<?=$bdata['idx']?>"><?=$bdata['title']?></a></li>
	<?}} else {?>베스트 게시물이 없습니다.<?}
} else if($PMLIST['PROC'] == 'insert'){
	/*if(date('H') > 18 || date('H') < 9){
		echo "작성불가";
		exit;
	}*/
	$PMLIST['TITLE'] = PM_DELHTML($_REQUEST['title']);
	$PMLIST['IMAGE'] = PM_DELHTML($_REQUEST['image']);
	$PMLIST['IMAGE_USE'] = intval($_REQUEST['image_use']);
	$PMLIST['CONTENT'] = $_REQUEST['content'];
	$PMLIST['TAG'] = PM_DELHTML($_REQUEST['tag']);
	$PMLIST['SOURCE'] = PM_DELHTML($_REQUEST['source']);
	$PMLIST['PRIMETIME'] = intval($_REQUEST['primetime']);
	$PMLIST['CHECKSUM'] = PM_DELHTML($_REQUEST['checksum']);
	$PMLIST['CHECKED_PAGE'] = PM_DELHTML($_REQUEST['checked_page']);
	$PMLIST['FB_PUBLISH'] = intval($_REQUEST['fp_publish']);
	// 체크된 페이지 보고 카테고리 분류
	$fb_page = explode("|", $PMLIST['CHECKED_PAGE']);
	$count = count($fb_page);
	for($fb_loop=0;$fb_loop<$count;$fb_loop++) {
		if($fb_page[$fb_loop] == 57 || $fb_page[$fb_loop] == 72) {
			$category_idx[$fb_loop] = 1;
			$category_title[$fb_loop] = "유머관련";
		}
		else if($fb_page[$fb_loop] == 41 || $fb_page[$fb_loop] == 44 || $fb_page[$fb_loop] == 49 || $fb_page[$fb_loop] == 73 || $fb_page[$fb_loop] == 95) {
			$category_idx[$fb_loop] = 2;
			$category_title[$fb_loop] = "연애관련";
		}
		else if($fb_page[$fb_loop] == 43) {
			$category_idx[$fb_loop] = 3;
			$category_title[$fb_loop] = "게임관련";
		}
		else if($fb_page[$fb_loop] == 39 || $fb_page[$fb_loop] == 63) {
			$category_idx[$fb_loop] = 4;
			$category_title[$fb_loop] = "사건사고";
		}
		else if($fb_page[$fb_loop] == 3 || $fb_page[$fb_loop] == 13 || $fb_page[$fb_loop] == 20 || $fb_page[$fb_loop] == 76) {
			$category_idx[$fb_loop] = 5;
			$category_title[$fb_loop] = "애완동물";
		}
		else if($fb_page[$fb_loop] == 45) {
			$category_idx[$fb_loop] = 6;
			$category_title[$fb_loop] = "여자관련";
		}
		else {
			$category_idx[$fb_loop] = 7;
			$category_title[$fb_loop] = "기타등등";
		}
	}
	$PMLIST['CATEGORY_IDX'] = PM_DELHTML(implode("|", $category_idx));
	$PMLIST['CATEGORY_TITLE'] = PM_DELHTML(implode("|", $category_title));

	$set_add = "";

	$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$PMLIST['CATEGORY_IDX']."'");
	if(mysqli_num_rows($category_qry) <= 0){
		echo "잘못된 카테고리를 선택하였습니다. 새로고침 후 다시 시도해보시기 바랍니다.";
		exit;
	}
	$category_info = mysqli_fetch_array($category_qry);

	if($PMLIST['IMAGE']){
		if($PMLIST['IMAGE_USE'] == 1) $set_add .= ", image_use = '1'";
		$img_exp = explode(',',$PMLIST['IMAGE']);
		for($i=0; $i<=count($img_exp) - 1; $i++){
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_exp[$i])){
				$dir_exp = explode('/',$img_exp[$i]);
				$dir_par = "";
				for($j=0; $j<count($dir_exp) - 1; $j++){
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$dir_par.'/'.$dir_exp[$j],"0707");
					if($dir_par != "") $dir_par .= "/";
					$dir_par .= $dir_exp[$j];
				}
				rename($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_exp[$i], $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$img_exp[$i]);
			} else {
				echo "이미지 업로드에 문제가 발생하여 중지합니다.\n이미지를 전체삭제하시고 다시 선택 후 게시물 등록을 해주세요.";
				exit;
			}
		}
	}

	$first_image = '';
	if($PMLIST['CONTENT'] != '' && strpos($PMLIST['CONTENT'],'img') !== false){
		$cnt = preg_match_all('@<img\s+.*?(src\s*=\s*("[^"\\\\]*(?:[^"\\\\]*)*"|\'[^\'\\\\]*(?:[^\'\\\\]*)*\'|[^\s]+)).*?>@is', $PMLIST['CONTENT'], $res);
		foreach($res[2] as $img){
			if(strpos($img,$_SERVER['HTTP_HOST']) !== false && strpos($img,'/uploaded/temp/') !== false){
				$img_src = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/','',str_replace('http://','',str_replace('\\','',str_replace('"','',$img))));
				if(is_file($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_src)){
					$dir_exp = explode('/',$img_src);
					$dir_par = "";
					for($j=0; $j<count($dir_exp) - 1; $j++){
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$dir_par.'/'.$dir_exp[$j],"0707");
						if($dir_par != "") $dir_par .= "/";
						$dir_par .= $dir_exp[$j];
					}
					rename($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_src, $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$img_src);
				} else {
					echo "이미지 업로드에 문제가 발생하여 중지합니다.\n상세내용 내 이미지를 다시 삽입하여 게시물 등록해주세요.";
					exit;
				}
			} else if(strpos($img,'data:image/') !== false){
				preg_match('/^data:image\/(.*?);base64,/', str_replace('\\"','',$img), $res);
				$ext = $res[1];
				$base64Data = preg_replace("/^data:image\/".$ext.";base64,/", "", str_replace('\\"','',$img)); //$base64Data = preg_replace("/^data:image\/(png|jpg|jpeg);base64,/", "", $img);
				@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y'),"0707");
				@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m'),"0707");
				@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d'),"0707");
				$filename = $MEM['idx']."_".mktime().mt_rand(100, 999).".".$ext;
				if(String2File(base64_decode($base64Data), $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename)){
					$PMLIST['CONTENT'] = str_replace($img,'http://'.$_SERVER['HTTP_HOST'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename,$PMLIST['CONTENT']);
					if($first_image == '') $first_image = date('Y').'/'.date('m').'/'.date('d').'/'.$filename;
				} else {
					echo "이미지 업로드에 문제가 발생하여 중지합니다.\n상세내용 내 이미지를 다시 삽입하여 게시물 등록해주세요.";
					exit;
				}
			}
			if($first_image == ''){
				if(strpos($img, $_SERVER['HTTP_HOST']) !== false){
					$first_image = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/','',str_replace('http://','',str_replace('\\','',str_replace('"','',$img))));
				} else {
					$first_image = str_replace('\\','',str_replace('"','',$img));
				}
			}
		}
	}

	if(!$PMLIST['IMAGE'] && $first_image != ''){
		$PMLIST['IMAGE'] = $first_image;
		if($PMLIST['IMAGE_USE'] == 1) $set_add .= ", image_use = '1'";
	}

	$PMLIST['CONTENT'] = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/',$_SERVER['HTTP_HOST'].'/uploaded/board/',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('script','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('SCRIPT','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('object','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('OBJECT','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('param','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('PARAM','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('embed','',$PMLIST['CONTENT']);
	//$PMLIST['CONTENT'] = str_replace('EMBED','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('select','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('SELECT','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('insert','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('INSERT','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('update','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace('UPDATE','',$PMLIST['CONTENT']);
	$PMLIST['CONTENT'] = str_replace("'","\\'",$PMLIST['CONTENT']);

	$PMLIST['TITLE'] = str_replace("'","\\'",$PMLIST['TITLE']);

	if($MEM['idx']){
		mysqli_query($connect,"INSERT INTO board SET
			user_idx					= '".$MEM['idx']."',
			user_id						= '".$MEM['id']."',
			user_name					= '".$MEM['name']."',
			user_nick					= '".$MEM['nick']."',
			type						= '0',
			category_idx				= '".$PMLIST['CATEGORY_IDX']."',
			category_title				= '".$PMLIST['CATEGORY_TITLE']."',
			title						= '".$PMLIST['TITLE']."',
			image						= '".$PMLIST['IMAGE']."',
			content						= '".$PMLIST['CONTENT']."',
			tag							= '".$PMLIST['TAG']."',
			source						= '".$PMLIST['SOURCE']."',
			primetime					= '".$PMLIST['PRIMETIME']."',
			facebook_page				= '".$PMLIST['CHECKED_PAGE']."',
			fb_publish					= '".$PMLIST['FB_PUBLISH']."',
			regdate						= NOW(),
			regtime						= NOW(),
			checksum					= '".$MEM['idx']."_".$PMLIST['CHECKSUM']."'"
			.$set_add);

		if(($row_id = mysqli_insert_id($connect)) > 0){
			if(($apoints = mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM point_history WHERE user_idx = '".$MEM['idx']."' AND type = '0' AND regtime > '".date('Y-m-d')."'"),0)) < 5){
				/*if(($nums = mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM board WHERE user_idx = '".$MEM['idx']."'"), 0)) == 1){
					$point = 500;
				} else {*/
					$point = 100;
				//}
				mysqli_query($connect,"INSERT INTO point_history SET user_idx = '".$MEM['idx']."', point = ".$point.", type = '0', contents_idx = '".$row_id."', memo = '게시글 작성', regtime = NOW(), regdate = NOW();");
				mysqli_query($connect,"UPDATE users SET point = point + ".$point." WHERE idx = '".$MEM['idx']."'");
			}
			if($PMLIST['CHECKED_PAGE']){
				$checked_page = explode('|',$PMLIST['CHECKED_PAGE']);
				foreach($checked_page as $page){
					mysqli_query($connect,"INSERT INTO facebook_page_article SET page_idx = ".$page.", article_idx = ".$row_id.", publish = '".$PMLIST['FB_PUBLISH']."', regtime = NOW()");
				}
			}
			echo "success||".$row_id;
		} else {
			echo "notinserted";
		}
	} else {
		echo "nologin";
	}
} else if($PMLIST['PROC'] == 'modify'){
	$PMLIST['TITLE'] = PM_DELHTML($_REQUEST['title']);
	$PMLIST['IMAGE'] = PM_DELHTML($_REQUEST['image']);
	$PMLIST['IMAGE_USE'] = intval($_REQUEST['image_use']);
	$PMLIST['CONTENT'] = $_REQUEST['content'];
	$PMLIST['TAG'] = PM_DELHTML($_REQUEST['tag']);
	$PMLIST['SOURCE'] = PM_DELHTML($_REQUEST['source']);
	$PMLIST['PRIMETIME'] = intval($_REQUEST['primetime']);
	$PMLIST['CHECKSUM'] = PM_DELHTML($_REQUEST['checksum']);
	$PMLIST['CHECKED_PAGE'] = PM_DELHTML($_REQUEST['checked_page']);
	$PMLIST['FB_PUBLISH'] = intval($_REQUEST['fp_publish']);
	// 체크된 페이지 보고 카테고리 분류
	$fb_page = explode("|", $PMLIST['CHECKED_PAGE']);
	$count = count($fb_page);
	for($fb_loop=0;$fb_loop<$count;$fb_loop++) {
		if($fb_page[$fb_loop] == 57 || $fb_page[$fb_loop] == 72) {
			$category_idx[$fb_loop] = 1;
			$category_title[$fb_loop] = "유머관련";
		}
		else if($fb_page[$fb_loop] == 41 || $fb_page[$fb_loop] == 44 || $fb_page[$fb_loop] == 49 || $fb_page[$fb_loop] == 73 || $fb_page[$fb_loop] == 95) {
			$category_idx[$fb_loop] = 2;
			$category_title[$fb_loop] = "연애관련";
		}
		else if($fb_page[$fb_loop] == 43) {
			$category_idx[$fb_loop] = 3;
			$category_title[$fb_loop] = "게임관련";
		}
		else if($fb_page[$fb_loop] == 39 || $fb_page[$fb_loop] == 63) {
			$category_idx[$fb_loop] = 4;
			$category_title[$fb_loop] = "사건사고";
		}
		else if($fb_page[$fb_loop] == 3 || $fb_page[$fb_loop] == 13 || $fb_page[$fb_loop] == 20 || $fb_page[$fb_loop] == 76) {
			$category_idx[$fb_loop] = 5;
			$category_title[$fb_loop] = "애완동물";
		}
		else if($fb_page[$fb_loop] == 45) {
			$category_idx[$fb_loop] = 6;
			$category_title[$fb_loop] = "여자관련";
		}
		else {
			$category_idx[$fb_loop] = 7;
			$category_title[$fb_loop] = "기타등등";
		}
	}

	$PMLIST['CATEGORY_IDX'] = PM_DELHTML(implode("|", $category_idx));
	$PMLIST['CATEGORY_TITLE'] = PM_DELHTML(implode("|", $category_title));

	$board_info = @mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM board WHERE idx = '".$PMLIST['IDX']."'"));
	if($MEM['level'] == 99 || $board_info['user_idx'] == $MEM['idx'] || $MEM['id'] == "minjilove"){
		$set_add = "";

		$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$PMLIST['CATEGORY_IDX']."'");
		if(mysqli_num_rows($category_qry) <= 0){
			echo "잘못된 카테고리를 선택하였습니다. 새로고침 후 다시 시도해보시기 바랍니다.";
			exit;
		}
		$category_info = mysqli_fetch_array($category_qry);

		if($PMLIST['IMAGE']){
			if($PMLIST['IMAGE_USE'] == 1){
				$set_add .= ", image_use = '1'";
			} else {
				$set_add .= ", image_use = '0'";
			}
			$img_exp = explode(',',$PMLIST['IMAGE']);
			for($i=0; $i<=count($img_exp) - 1; $i++){
				if(is_file($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_exp[$i])){
					$dir_exp = explode('/',$img_exp[$i]);
					$dir_par = "";
					for($j=0; $j<count($dir_exp) - 1; $j++){
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$dir_par.'/'.$dir_exp[$j],"0707");
						if($dir_par != "") $dir_par .= "/";
						$dir_par .= $dir_exp[$j];
					}
					rename($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_exp[$i], $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$img_exp[$i]);
				}
			}
		}

		$first_image = '';
		if($PMLIST['CONTENT'] != '' && strpos($PMLIST['CONTENT'],'img') !== false){
			$cnt = preg_match_all('@<img\s+.*?(src\s*=\s*("[^"\\\\]*(?:[^"\\\\]*)*"|\'[^\'\\\\]*(?:[^\'\\\\]*)*\'|[^\s]+)).*?>@is', $PMLIST['CONTENT'], $res);
			foreach($res[2] as $img){
				if(strpos($img,$_SERVER['HTTP_HOST']) !== false && strpos($img,'/uploaded/temp/') !== false){
					$img_src = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/','',str_replace('http://','',str_replace('\\','',str_replace('"','',$img))));
					if(is_file($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_src)){
						$dir_exp = explode('/',$img_src);
						$dir_par = "";
						for($j=0; $j<count($dir_exp) - 1; $j++){
							@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$dir_par.'/'.$dir_exp[$j],"0707");
							if($dir_par != "") $dir_par .= "/";
							$dir_par .= $dir_exp[$j];
						}
						rename($_SERVER['DOCUMENT_ROOT'].'/uploaded/temp/'.$img_src, $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.$img_src);
					} else {
						echo "이미지 업로드에 문제가 발생하여 중지합니다.\n상세내용 내 이미지를 다시 삽입하여 게시물 등록해주세요.";
						exit;
					}
				} else if(strpos($img,'data:image/') !== false){
					preg_match('/^data:image\/(.*?);base64,/', str_replace('\\"','',$img), $res);
					$ext = $res[1];
					$base64Data = preg_replace("/^data:image\/".$ext.";base64,/", "", str_replace('\\"','',$img)); //$base64Data = preg_replace("/^data:image\/(png|jpg|jpeg);base64,/", "", $img);
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y'),"0707");
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m'),"0707");
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d'),"0707");
					$filename = $MEM['idx']."_".mktime().mt_rand(100, 999).".".$ext;
					if(String2File(base64_decode($base64Data), $_SERVER['DOCUMENT_ROOT'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename)){
						$PMLIST['CONTENT'] = str_replace($img,'http://'.$_SERVER['HTTP_HOST'].'/uploaded/board/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename,$PMLIST['CONTENT']);
						if($first_image == '') $first_image = date('Y').'/'.date('m').'/'.date('d').'/'.$filename;
					} else {
						echo "이미지 업로드에 문제가 발생하여 중지합니다.\n상세내용 내 이미지를 다시 삽입하여 게시물 등록해주세요.";
						exit;
					}
				}
				if($first_image == ''){
					if(strpos($img, $_SERVER['HTTP_HOST']) !== false){
						$first_image = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/','',str_replace('http://','',str_replace('\\','',str_replace('"','',$img))));
					} else {
						$first_image = str_replace('\\','',str_replace('"','',$img));
					}
				}
			}
		}

		if(!$PMLIST['IMAGE'] && $first_image != ''){
			$PMLIST['IMAGE'] = $first_image;
			if($PMLIST['IMAGE_USE'] == 1) $set_add .= ", image_use = '1'";
		}

		$PMLIST['CONTENT'] = str_replace($_SERVER['HTTP_HOST'].'/uploaded/temp/',$_SERVER['HTTP_HOST'].'/uploaded/board/',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('script','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('SCRIPT','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('object','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('OBJECT','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('param','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('PARAM','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('embed','',$PMLIST['CONTENT']);
		//$PMLIST['CONTENT'] = str_replace('EMBED','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('select','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('SELECT','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('insert','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('INSERT','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('update','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace('UPDATE','',$PMLIST['CONTENT']);
		$PMLIST['CONTENT'] = str_replace("'","\\'",$PMLIST['CONTENT']);

		$PMLIST['TITLE'] = str_replace("'","\\'",$PMLIST['TITLE']);

		if($MEM['idx']){
			mysqli_query($connect,"UPDATE board SET
				type						= '0',
				category_idx				= '".$PMLIST['CATEGORY_IDX']."',
				category_title				= '".$PMLIST['CATEGORY_TITLE']."',
				title						= '".$PMLIST['TITLE']."',
				image						= '".$PMLIST['IMAGE']."',
				content						= '".$PMLIST['CONTENT']."',
				tag							= '".$PMLIST['TAG']."',
				source						= '".$PMLIST['SOURCE']."',
				primetime					= '".$PMLIST['PRIMETIME']."',
				facebook_page				= '".$PMLIST['CHECKED_PAGE']."',
				fb_publish					= '".$PMLIST['FB_PUBLISH']."',
				modifytime					= NOW()"
				.$set_add.
				" WHERE idx = '".$PMLIST['IDX']."'");

			if($PMLIST['CHECKED_PAGE']){
				$checked_page = explode('|',$PMLIST['CHECKED_PAGE']);
				foreach($checked_page as $page){
					$sql = mysqli_query($connect,"SELECT * FROM facebook_page_article WHERE page_idx = ".$page." AND article_idx = ".$PMLIST['IDX']);
					if(mysqli_num_rows($sql) > 0){
						$row = mysqli_fetch_array($sql);
						if($row['status'] == '0'){
							mysqli_query($connect,"UPDATE facebook_page_article SET publish = '".$PMLIST['FB_PUBLISH']."' WHERE page_idx = ".$page." AND article_idx = ".$PMLIST['IDX']);
						} else if($row['status'] == '1' || $row['status'] == '2' || $row['status'] == '4' || $row['status'] == '5'){
							mysqli_query($connect,"UPDATE facebook_page_article SET publish = '".$PMLIST['FB_PUBLISH']."', status = '4' WHERE page_idx = ".$page." AND article_idx = ".$PMLIST['IDX']);
						} else if($row['status'] == '3'){
							mysqli_query($connect,"UPDATE facebook_page_article SET publish = '".$PMLIST['FB_PUBLISH']."', status = '0', article_id = NULL WHERE page_idx = ".$page." AND article_idx = ".$PMLIST['IDX']);
						}
					} else {
						mysqli_query($connect,"INSERT INTO facebook_page_article SET page_idx = ".$page.", article_idx = ".$PMLIST['IDX'].", publish = '".$PMLIST['FB_PUBLISH']."', regtime = NOW() ON DUPLICATE KEY UPDATE status = '4'");
					}
				}
				$page_idxs = str_replace("|",",",$PMLIST['CHECKED_PAGE']);
				mysqli_query($connect, "UPDATE facebook_page_article SET status = '2' WHERE article_idx = ".$PMLIST['IDX']." AND page_idx NOT IN (".$page_idxs.") AND article_id IS NOT NULL");
				mysqli_query($connect, "UPDATE facebook_page_article SET status = '3' WHERE article_idx = ".$PMLIST['IDX']." AND page_idx NOT IN (".$page_idxs.") AND article_id IS NULL");
			} else {
				mysqli_query($connect, "UPDATE facebook_page_article SET status = '2' WHERE article_idx = ".$PMLIST['IDX']." AND article_id IS NOT NULL");
				mysqli_query($connect, "UPDATE facebook_page_article SET status = '3' WHERE article_idx = ".$PMLIST['IDX']." AND article_id IS NULL");
			}

			echo "success||".$PMLIST['IDX'];
		} else {
			echo "nologin";
		}
	} else {
		echo "nowriter";
	}
} else if($PMLIST['PROC'] == 'delete'){
	if(!$MEM['idx']){
		echo "nologin";
		exit;
	}
	$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE idx = '".$PMLIST['IDX']."' AND status = '0'");
	if(mysqli_num_rows($board_qry) > 0){
		$board_info = mysqli_fetch_array($board_qry);
		if($MEM['idx'] == $board_info['user_idx'] || $MEM['level'] == 99 || $MEM['id'] == "minjilove"){
			mysqli_query($connect,"UPDATE board SET status = '1' WHERE idx = '".$PMLIST['IDX']."'");

			mysqli_query($connect, "UPDATE facebook_page_article SET status = '2' WHERE article_idx = ".$PMLIST['IDX']." AND page_idx NOT IN (".$page_idxs.") AND article_id IS NOT NULL");
			mysqli_query($connect, "UPDATE facebook_page_article SET status = '3' WHERE article_idx = ".$PMLIST['IDX']." AND page_idx NOT IN (".$page_idxs.") AND article_id IS NULL");

			$point_sum = @mysqli_result(mysqli_query($connect,"SELECT SUM(point) FROM point_history WHERE contents_idx = '".$PMLIST['IDX']."' AND type IN ('0','1')"),0);
			mysqli_query($connect,"INSERT INTO point_history SET user_idx = '".$board_info['user_idx']."', point = ".$point_sum.", `use` = '1', contents_idx = '".$PMLIST['IDX']."', type = '6', memo = '게시글 삭제', regtime = NOW(), regdate = NOW();");
			mysqli_query($connect,"UPDATE users SET point = point - ".$point_sum." WHERE idx = '".$board_info['user_idx']."'");

			echo "success";
		} else {
			echo "nowriter";
			exit;
		}
	} else {
		echo "noarticle";
	}
} else if($PMLIST['PROC'] == 'get_content'){
	$board_qry = mysqli_query($connect,"SELECT * FROM board WHERE idx = '".$PMLIST['IDX']."' AND status = '0'");
	if(mysqli_num_rows($board_qry) > 0){
		$board_info = mysqli_fetch_array($board_qry);
		echo $board_info['content'];
	} else {
		echo "noarticle";
	}
} else if($PMLIST['PROC'] == '1to1_write'){
	sqlsrv_query($connect, "INSERT INTO board_master (category_id,board_cdate,board_title,board_caption,board_count_read,board_count_cmt,board_type,user_ip,UserId) VALUES ('5',GetDate(),'".$PMLIST['TITLE']."','".$PMLIST['TEXT']."','0','0','1','".$_SERVER['REMOTE_ADDR']."','".$MEM['UserId']."')");
	if(sqlsrv_insert_id() > 0){
		echo "success";
	} else {
		echo "DB입력 중에 문제가 발생하였습니다.\n잠시 후 다시 시도해보시기 바랍니다.";
	}
} else if($PMLIST['PROC'] == 'faq'){
	if($PMLIST['SEARCH'] && $PMLIST['MODE']){
		$search_kwd = str_replace('[','[[]',str_replace(']','[]]',str_replace('%','[%]',$PMLIST['SEARCH'])));
		$search_add = " AND ".$PMLIST['MODE']." LIKE '%".$search_kwd."%'";
	}

	$all_nums = sqlsrv_fetch_array(sqlsrv_query($connect, "SELECT COUNT(board_id) FROM board_master WHERE category_id = '2'".$search_add));
	if($all_nums[0] == 0){?>
	<tr>
		<td colspan="3" style="height:57px;">게시물이 없습니다.</td>
	</tr>
	<?} else {
		$all_nums = $all_nums[0]; $all_pages = ceil($all_nums/10);
		if($PMLIST['PAGE'] > $all_pages) $PMLIST['PAGE'] = $all_pages;
		$start_num = ($PMLIST['PAGE']-1)*10+1; $end_num = ($start_num+9>$all_nums)?$all_nums:$start_num+9;

		$board_qry = sqlsrv_query($connect, "SELECT *, CONVERT(CHAR(10), board_cdate, 102) AS cdate FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY board_id DESC) AS QQ, ROW_NUMBER() OVER (ORDER BY board_id ASC) AS QQ2 FROM board_master WHERE category_id = '2'".$search_add.") AS T WHERE QQ BETWEEN ".$start_num." AND ".$end_num." ORDER BY QQ ASC");

		if(sqlsrv_has_rows($board_qry) > 0){
			while($bdata = sqlsrv_fetch_array($board_qry)){?>
		<tr idx="<?=$bdata['board_id']?>">
			<td><?=$bdata['QQ2']?></td>
			<td class="text"><?=$bdata['board_title']?></td>
			<td><img src="/images/faq/faq_closed.png" /></td>
		</tr>
		<tr class="opened" idx="<?=$bdata['board_id']?>">
			<td></td>
			<td class="text"><div><?=nl2br($bdata['board_caption'])?></div></td>
			<td></td>
		</tr>
			<?}
		}
		echo "||^||";

		$start_decades_page = floor(($PMLIST['PAGE']-1)/10)*10+1;
		$end_decades_page = ($start_decades_page+9 > $all_pages)?$all_pages:$start_decades_page+9;?>
	<img src="/images/common/page_prev.png" class="page_prev" />
		<?for($i=$start_decades_page;$i<=$end_decades_page;$i++){?>
		<span<?if($PMLIST['PAGE'] == $i){?> class="now"<?}?>><?=$i?></span>
		<?}?>
	<img src="/images/common/page_next.png" class="page_next" />
	<?}
} elseif($PMLIST['PROC'] == 'recommend'){
	if($MEM['idx']){
		if($PMLIST['TYPE'] == 'recommend'){
			$YN = 'Y';
		} else if($PMLIST['TYPE'] == 'not_recommend'){
			$YN = 'N';
		}
		$qry = mysqli_query($connect,"SELECT * FROM recommend WHERE user_idx = '".$MEM['idx']."' AND article_idx = '".$PMLIST['IDX']."' AND YN = '".$YN."' AND type='board'");
		if(mysqli_num_rows($qry) > 0){
			echo "already";
		} else {
			mysqli_query($connect,"INSERT INTO recommend SET user_idx = '".$MEM['idx']."', article_idx = '".$PMLIST['IDX']."', type='board', YN = '".$YN."', regtime = NOW() ON DUPLICATE KEY UPDATE regtime = NOW(), YN = '".$YN."'");
			$reco_count = mysqli_result(mysqli_query($connect,"SELECT COUNT(user_idx) FROM recommend WHERE article_idx = '".$PMLIST['IDX']."' AND type = 'board' AND YN = 'Y'"),0);
			mysqli_query($connect,"UPDATE board SET recommend_count = ".$reco_count." WHERE idx = '".$PMLIST['IDX']."'");
			$unreco_count = mysqli_result(mysqli_query($connect,"SELECT COUNT(user_idx) FROM recommend WHERE article_idx = '".$PMLIST['IDX']."' AND type = 'board' AND YN = 'N'"),0);
			mysqli_query($connect,"UPDATE board SET unreco_count = ".$unreco_count." WHERE idx = '".$PMLIST['IDX']."'");
			echo "success||".$reco_count."||".$unreco_count;
		}
	} else {
		echo "로그인 후 이용하세요!";
	}
} else if($PMLIST['PROC'] == 'read_com' && $_REQUEST['idx']){
	$comment = mysqli_query($connect,"SELECT *, UNIX_TIMESTAMP(regtime) utime, DATE_FORMAT(regtime,'%Y-%m-%d') regdate FROM comment WHERE article_idx = '".$PMLIST['IDX']."' AND type = '".$PMLIST['TYPE']."' AND status = '0' ORDER BY num DESC LIMIT ".(($PMLIST['PAGE']-1)*20).", 20");
	if(($comment_count = mysqli_num_rows($comment)) > 0){
		while($row = mysqli_fetch_array($comment)){
			$user_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM users WHERE `idx` = '".$row['user_idx']."'"));
			$thumb_img = (!$user_info['thumb'])?'/images/pic.jpg':'/uploaded/thumb/user/'.$user_info['thumb'];?>
				<li id="comm_<?=$row['idx']?>">
					<dl>
						<div class="thumb" style="background-image:url(<?=$thumb_img?>); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$thumb_img?>',sizingMethod='scale'); -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$thumb_img?>', sizingMethod='scale');"></div>
					</dl>
					<dt>
						<p>
							<span><?=$user_info['nick']?$user_info['nick']:$user_info['name']?></span> | <?=$row['regdate']?> | <b><a href="javascript:" class="btn_like" onclick="reco_com(<?=$row['idx']?>,<?=$PMLIST['IDX']?>)"></a> <span class="recom_cnt"><?=number_format($row['reco'])?></span></b> | <span style="color:#919191; font-size:12px; font-weight:500;" utime="<?=$row['utime']?>"><?=time_diff($row['utime'])?></span>
						</p>
						<div>
							<?=nl2br($row['text'])?>
						</div>
					</dt>
					<dd>
						<a href="#">신고</a><?if($MEM['idx'] == $row['user_idx']){?>
							<a href="javascript:" onclick="del_comment(<?=$row['idx']?>,<?=$PMLIST['IDX']?>,'<?=$PMLIST['TYPE']?>')">삭제</a><?}?>
					</dd>
				</li>
		<?}
	} else {?>
				<!--div class="comment"><p class="reply_none">등록된 댓글이 없습니다.</p></div-->
	<?}
	echo "||".$comment_count;
} else if($PMLIST['PROC'] == 'write_com' && $_REQUEST['idx']){
	if(!$MEM['idx']){ echo "nologin"; exit; }
	if(!$PMLIST['TEXT']) { echo "댓글을 입력하세요"; exit; }
	$num_cou = @mysqli_result(mysqli_query($connect,"SELECT MAX(num) FROM comment WHERE article_idx = '".$PMLIST['IDX']."' AND type = '".$PMLIST['TYPE']."'"),0);
	$ins_qry = mysqli_query($connect,"INSERT INTO comment SET article_idx = '".$PMLIST['IDX']."', type = '".$PMLIST['TYPE']."', user_idx = '".$MEM['idx']."', text = '".$PMLIST['TEXT']."', num = '".($num_cou + 1)."', keylog = '".$MEM['idx']."_".$PMLIST['KEYLOG']."', regtime = NOW()");
	if(($cidx = mysqli_insert_id($connect))){
		if(($cpoints = mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM point_history WHERE user_idx = '".$MEM['idx']."' AND type = '2' AND regtime > '".date('Y-m-d')."'"),0)) < 10){
			mysqli_query($connect,"INSERT INTO point_history SET user_idx = '".$MEM['idx']."', point = 20, type = '2', contents_idx = '".$cidx."', memo = '댓글 작성', regtime = NOW(), regdate = NOW();");
			mysqli_query($connect,"UPDATE users SET point = point + 20 WHERE idx = '".$MEM['idx']."'");
		}
		$comment_count = @mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM comment WHERE article_idx = '".$PMLIST['IDX']."' AND type = '".$PMLIST['TYPE']."' AND status = '0'"),0);
		$up_qry = mysqli_query($connect,"UPDATE ".$PMLIST['TYPE']." SET comment_count = ".$comment_count." WHERE idx = '".$PMLIST['IDX']."'");
		echo "success||".mktime();
	}
} else if($PMLIST['PROC'] == 'delete_com' && $_REQUEST['idx']){
	if(!$MEM['idx']){ echo "nologin"; exit; }
	mysqli_query($connect,"UPDATE comment SET status = '1' WHERE article_idx = '".$PMLIST['ARTICLE_IDX']."' AND type = '".$PMLIST['TYPE']."' AND user_idx = '".$MEM['idx']."' AND idx = '".$PMLIST['IDX']."'");

	$comment_count = @mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM comment WHERE article_idx = '".$PMLIST['ARTICLE_IDX']."' AND type = '".$PMLIST['TYPE']."' AND status = '0'"),0);
	mysqli_query($connect,"UPDATE ".$PMLIST['TYPE']." SET comment_count = ".$comment_count." WHERE idx = '".$PMLIST['ARTICLE_IDX']."'");

	$point_sum = @mysqli_result(mysqli_query($connect,"SELECT SUM(point) FROM point_history WHERE contents_idx = '".$PMLIST['IDX']."' AND user_idx = '".$MEM['idx']."' AND type IN ('2','3')"),0);
	mysqli_query($connect,"INSERT INTO point_history SET user_idx = '".$MEM['idx']."', point = ".$point_sum.", `use` = '1', contents_idx = '".$PMLIST['IDX']."', type = '6', memo = '댓글 삭제', regtime = NOW(), regdate = NOW();");
	mysqli_query($connect,"UPDATE users SET point = point - ".$point_sum." WHERE idx = '".$MEM['idx']."'");

	echo "success||".mktime();
} elseif($_REQUEST['idx'] && $PMLIST['PROC'] == 'reco_com'){
	if(!$MEM['idx']){ echo "nologin"; exit; }
	$ins_qry = mysqli_query($connect,"INSERT INTO comment_like SET comment_idx = '".$PMLIST['IDX']."', user_idx = '".$MEM['idx']."', regtime = NOW()");
	if(mysqli_insert_id($connect)){
		$like_count = @mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM comment_like WHERE comment_idx = '".$PMLIST['IDX']."' AND user_idx = '".$MEM['idx']."'"),0);
		$up_qry = mysqli_query($connect,"UPDATE comment SET reco = ".$like_count." WHERE idx = '".$PMLIST['IDX']."'");

		$comment_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM comment WHERE idx = '".$PMLIST['IDX']."'"));
		echo "success||".mktime()."||".$comment_info['article_idx']."||".$comment_info['type'];
	} else {
		echo "이미 추천하신 댓글입니다.";
	}
}?>
