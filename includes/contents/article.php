<?if(!$PMLIST['CATEGORY']) $PMLIST['CATEGORY'] = 'all';?>
<div class="layout2_l">
	<!--div id="sky">
		<a target="_blank" href="http://click.linkprice.com/click.php?m=replicas1&a=A100528923&l=0031&u_id="><img src="http://replic.godohosting.com/linkprice/home/160×600.jpg" border="0" width="160" height="600"></a><img src="http://track.linkprice.com/lpshow.php?m_id=replicas1&a_id=A100528923&p_id=0000&l_id=0031&l_cd1=2&l_cd2=0" width="1" height="1" border="0" nosave style="display:none">
	</div-->
	<div class="sub_contents">
		<?$board_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y-%m-%d') AS regdate FROM board WHERE idx = '".$PMLIST['IDX']."' AND type = '0' AND status = '0'");
		if(mysqli_num_rows($board_qry) > 0){
			$view_count = 20; // 게시판 리스트 보이는 글 수

			$board_info = mysqli_fetch_array($board_qry);
			mysqli_query($connect,"UPDATE board SET view_count = view_count + 1 WHERE idx = '".$PMLIST['IDX']."'");
			$category_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE idx = '".$board_info['category_idx']."'"));
			$writer_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM users WHERE idx = '".$board_info['user_idx']."'"));

			$where_add = "";
			if($PMLIST['CATEGORY'] != 'all'){
				$input_cate_info = mysqli_fetch_array(mysqli_query($connect,"SELECT * FROM category WHERE code = '".$PMLIST['CATEGORY']."'"));
				$where_add = " AND category_idx LIKE '%".$input_cate_info['idx']."%'";
			}
			$before_articles = @mysqli_result(mysqli_query($connect,"SELECT count(idx) FROM board WHERE status = '0' AND idx >= ".$PMLIST['IDX'].$where_add),0);
			$current_page = ceil($before_articles/$view_count);?>
		<div class="list_title">
			<?=$category_info['title']?>
		</div>
		<div class="article_title"><?=$board_info['title']?></div>
		<div class="txt2">[<b onclick=location.href="/?inc=list&type=normal&category=all&search=<?=$writer_info['nick']?$writer_info['nick']:$writer_info['name']?>&mode=writer&page=1" style="cursor:pointer; font-weight:600;"><?=$writer_info['nick']?$writer_info['nick']:$writer_info['name']?></b>] 입력 <?=$board_info['regdate']?>&nbsp;&nbsp;&nbsp;조회 <?=number_format($board_info['view_count'])?>&nbsp;&nbsp;&nbsp;추천 <?=number_format($board_info['recommend_count'])?></div>
		<div class="txt3">
			<strong><img src="images/dot.jpg" alt="" style="margin-left:0;" />출처 : <?=$board_info['source']?$board_info['source']:"뒷북"?><img src="images/dot.jpg" alt="" /> 저작권 문의 : backdrumdelete@gmail.com</strong>
		</div>
		<div class="contents_box1">
		<?if($board_info['image'] && $board_info['image_use'] == '1'){
			$images = explode(',',$board_info['image']);
			foreach($images as $img){?>
			<img src="/uploaded/board/<?=$img?>" style="max-width:100%; display:block; margin:0 auto; margin-bottom:1em;" />
		<?}}?>
			<?=str_replace("{fb:ad}","",preg_replace("/<p(.*)>{fb:ad}<\/p>/","",preg_replace('/<p(.*)>&nbsp;<\/p>\n\n<p(.*)>{fb:ad}<\/p>/','',preg_replace('@<img@is','<img alt="'.$board_info['title'].'"',$board_info['content']))));?>
		</div>
		<div id="list_btns" style="height:22px; width:100%; margin-bottom:15px; position:relative;">
			<!--a href="javascript:" onclick=location.href='/?inc=list&category=<?=$category_info['code']?>&page=<?=$current_page?>' class="btn1" style="position:relative; float:left; display:inline-block; margin:0; top:0;">목록</a-->
			<!-- $MEM['level'] == 99 ||  !-->
			<? if($writer_info['idx'] == $MEM['idx'] || $MEM['id'] == "minjilove"){?><a href="javascript:" id="delete_article" class="btn2" style="position:relative; float:right; display:inline-block; margin:0; top:0; margin-right:5px; right:25px;">삭제</a><a href="/?inc=modify&idx=<?=$PMLIST['IDX']?>" class="btn2" style="position:relative; float:right; display:inline-block; margin:0; top:0; margin-right:5px; right:25px;">수정</a><?}?>
			<? if($MEM['idx'] && empty($_SESSION["mem_user"])) {?><a href="/?inc=write&category=<?=$category_info['code']?>" id="write_article" class="btn2" style="position:relative; float:right; display:inline-block; margin:0; top:0; margin-right:5px; right:25px;">글쓰기</a><?}?>
			<!-- if($MEM['idx']) !-->
		</div>
		<!--div class="contents_box3">
			<a href="javascript:" id="recommend" class="rec_box"><img src="/images/heart1.jpg" alt="" /><font id="recommend_count"><?=number_format($board_info['recommend_count'])?></font><span>추천</span></a><a href="javascript:" id="not_recommend" class="rec_box"><img src="/images/heart2.jpg" alt="" /><font id="not_recommend_count"><?=number_format($board_info['unreco_count'])?></font><span>비추천</span></a>
		</div-->
		<script>
		$(document).ready(function(){
			$('.rec_box').click(function(){
				var rec_txt = "";
				if($(this).attr('id') == 'recommend'){
					rec_txt = "추천";
				} else if($(this).attr('id') == 'not_recommend'){
					rec_txt = "비추천";
				}

				$.post('/includes/ajax_loads/board.php',{'proc':'recommend', 'type':$(this).attr('id'), 'time':mktime(), 'idx':<?=$PMLIST['IDX']?>},function(data){
					var data_split = data.split('||');
					if(data_split[0] == 'nologin'){
						alert('로그인 후 이용하세요!');
					} else if(data_split[0] == 'already') {
						alert('이미 이 게시물을 '+rec_txt+'하셨습니다.');
					} else if(data_split[0] == 'success') {
						alert('해당 게시물을 '+rec_txt+'하였습니다.');
						$('#recommend_count').text(number_format(data_split[1]));
						$('#not_recommend_count').text(number_format(data_split[2]));
					} else {
						alert(data);
					}
				});
			});
			$('#delete_article').click(function(){
				if(confirm('한 번 삭제한 자료는 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')){
					$.post('/includes/ajax_loads/board.php', {'time':mktime(), 'proc':'delete', 'idx':<?=$PMLIST['IDX']?>}, function(data){
						if(data == 'nologin'){
							alert('로그인 세션이 유실되어 삭제에 실패하였습니다.\n로그인 후 다시 시도해보시기 바랍니다.');
							location.reload();
						} else if(data == 'nowriter'){
							alert('작성자가 아닙니다.');
							location.reload();
						} else if(data == 'noarticle'){
							alert('이미 삭제되었거나, 존재하지 않는 게시물입니다.');
							location.reload();
						} else if(data == 'success'){
							alert('삭제되었습니다.');
							location.href = '/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=<?=$current_page?>';
						} else {
							alert(data);
						}
					});
				}
			});
		});
		</script>
		<div class="contents_box5" style="border-top:1px solid #ccc;">
			<input id="keylog" type="hidden" value="<?=mktime()?>">
			<?$wrote_count = @mysqli_result(mysqli_query($connect,"SELECT COUNT(idx) FROM comment WHERE article_idx = '".$PMLIST['IDX']."' AND type = 'board' AND status = '0'"),0);?>
			<dl>
				댓글 <span>(총 <b id="comment_count"><?=number_format($wrote_count)?></b>개)</span>
			</dl>
		</div>
		<div class="reply_box">
			<div>
				<dl>
					<textarea class="comment_text" idx="0" maxlength="140" placeholder="댓글을 작성해주세요!"></textarea>
					<p>
						<!--input type="checkbox" id="chk2" /><label for="chk2"> 트위터</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* 선택하시면 함께 등록 됩니다.-->
						* 욕설 및 비방 등을 입력하시면, 댓글 작성을 금지당할 수 있습니다.
						<span class="comment_txt_count"><b id="cmt_txt_cnt_0">0 </b>/ 140자</span>
					</p>
				</dl>
				<dl><a href="javascript:" class="comment_done" idx="0">댓글 등록</a></dl>
			</div>
		</div>
		<ul id="comment_list" class="reply_box2"></ul>
		<script>
			$(document).ready(function(){
				get_comment('<?=$PMLIST['IDX']?>','board');

				$('.comment_done').click(function(){
					insert_comment('<?=$PMLIST['IDX']?>','board',$('textarea.comment_text[idx='+$(this).attr('idx')+']'));
				});
			});
		</script>
		<div class="real_content" style="border-top:1px solid #ccc; padding-bottom:15px;">
			<table class="table1 list<?if($PMLIST['TYPE'] != 'best'){?> table_top<?}?>">
				<colgroup>
					<col width="71">
					<?if($PMLIST['CATEGORY'] == 'all'){?><col width="100"><?}?>
					<col width="*">
					<col width="75">
					<col width="72">
					<col width="55">
				</colgroup>
				<thead>
					<tr>
						<th><span>번호</span></th>
						<?if($PMLIST['CATEGORY'] == 'all'){?><th><span>분류</span></th><?}?>
						<th><span>제목</span></th>
						<th><span>이름</span></th>
						<th><span>날짜</span></th>
						<th><span>조회</span></th>
					</tr>
				</thead>
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
						<th<?if($PMLIST['CATEGORY'] == 'all'){?> colspan="2"<?}?>><a href="/?inc=article&idx=<?=$nrow['idx']?>" class="onlist" style="line-height:normal;"><?=$nrow['title']?></a></th>
						<td><?=$nrow['user_nick']?></td>
						<td><?=$nrow['cdate']?></td>
						<td><?=number_format($nrow['view_count'])?></td>
						<td><?=number_format($nrow['recommend_count'])?></td>
					</tr>
						<?}
					}
					$all_nums = mysqli_fetch_array(mysqli_query($connect,"SELECT COUNT(idx) FROM board WHERE status ='0'".$where_add));
					if(($all_nums = $all_nums[0]) == 0){?>
					<tr>
						<td colspan="6" style="height:57px;">게시물이 없습니다.</td>
					</tr>
					<?} else {
						$all_pages = ceil($all_nums/$view_count);
						if($current_page > $all_pages) $current_page = $all_pages;
						$start_num = ($current_page - 1)*$view_count;

						$board_qry = mysqli_query($connect,"SELECT *, DATE_FORMAT(regtime,'%Y.%m.%d') cdate FROM board WHERE status ='0'".$where_add." ORDER BY idx DESC LIMIT ".$start_num.",".$view_count);

						if(($result_count = mysqli_num_rows($board_qry)) > 0){
							$i=0;
							while($bdata = mysqli_fetch_array($board_qry)){?>
					<tr>
						<td><b><?=$all_nums - $start_num - $i?></b></td>
						<?if($PMLIST['CATEGORY'] == 'all'){?><td><?=$category_info['title']?></td><?}?>
						<th>
							<a href="/?inc=article&idx=<?=$bdata['idx']?>&category=<?=$PMLIST['CATEGORY']?>" class="onlist" style="line-height:normal;<?if($bdata['idx'] == $board_info['idx']){?>color:#d90000;font-weight:600;<?}?>"><?=$bdata['title']?><?if($bdata['comment_count'] > 0){?><font style="font-weight:600; color:#d90000;">(<?=number_format($bdata['comment_count'])?>)</font><?}?></a>
							<?if($bdata['cdate'] == date("Y.m.d")){?><img src="/images/icon1.png" alt="" /><?}?>
							<?if($bdata['image_use'] == '1'){?><img src="/images/icon2.png" alt="" /><?}?>
						</th>
						<td><?=$bdata['user_nick']?$bdata['user_nick']:$bdata['user_name']?></td>
						<td><?=$bdata['cdate']?></td>
						<td><?=number_format($bdata['view_count'])?></td>
					</tr>
							<?$i++;}
						}
					}?>
				</tbody>
			</table>
			<?if($all_nums > 0){?>
			<div class="paging">
				<?$start_decades_page = floor(($current_page - 1)/10)*10+1;
				$end_decades_page = ($start_decades_page+9 > $all_pages)?$all_pages:$start_decades_page+9;?>
				<?if($current_page != 1){?><a href="/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=1"><img src="/images/first.jpg" alt="" /></a><?}?><?if($current_page != 1){?><a href="/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=<?=$current_page - 1?>"><img src="/images/prev.jpg" alt="" /></a><?}?><?for($i=$start_decades_page;$i<=$end_decades_page;$i++){?><a href="/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=<?=$i?>"<?if($current_page == $i){?> class="on"<?}?>><?=$i?></a><?}?><?if($current_page != $all_pages){?><a href="/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=<?=$current_page + 1?>"><img src="/images/next.jpg" alt="" /></a><?}?><?if($current_page != $all_pages){?><a href="/?inc=list&category=<?=$PMLIST['CATEGORY']?>&page=<?=$all_pages?>"><img src="/images/last.jpg" alt="" /></a><?}?>
			</div>
			<?}?>
		</div>
	</div>
	<?} else {?>
	삭제되었거나 존재하지 않는 게시물입니다.
	<?}?>
</div>

<script>
$(document).ready(function(){
	$('textarea.comment_text').keypress(function(){
		$('#cmt_txt_cnt_'+$(this).attr('idx')).text($(this).val().length);
	});
	$('textarea.comment_text').keyup(function(){
		$('#cmt_txt_cnt_'+$(this).attr('idx')).text($(this).val().length);
	});
	$('div.category#cate_<?=$PMLIST['CATEGORY']?>').addClass('on');
});
</script>

<?include($_SERVER['DOCUMENT_ROOT'].'/includes/contents/right_contents.php');?>
