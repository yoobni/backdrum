<div class="layout2_l">
	<!--div id="sky">
		<a target="_blank" href="http://click.linkprice.com/click.php?m=replicas1&a=A100528923&l=0031&u_id="><img src="http://replic.godohosting.com/linkprice/home/160×600.jpg" border="0" width="160" height="600"></a><img src="http://track.linkprice.com/lpshow.php?m_id=replicas1&a_id=A100528923&p_id=0000&l_id=0031&l_cd1=2&l_cd2=0" width="1" height="1" border="0" nosave style="display:none">
	</div-->
	<div class="sub_contents">
		<div class="list_title" id="best_top">베스트 뒷북</div>
		<ol class="l2_list" style="padding:1.8em;">
			<div style="width:32%; float:left; margin-right:3%;">
				<a class="lart">
					<div class="thumb"></div>
				</a>
			</div>
			<div id="cate_best" style="width:65%; float:left;"></div>
		</ol>
		<div style="clear:both;"></div>
		<div class="list_title" id="list_top"><?=$PMCATE[$PMLIST['CATEGORY']]?> 게시물</div>
		<div class="real_content" id="ajax_contents"></div>
		<div class="search_box">
			<?if($MEM['idx'] && empty($_SESSION["mem_user"])){?><a href="/?inc=write&category=<?=$PMLIST['CATEGORY']?$PMLIST['CATEGORY']:"all"?>" id="write_btn" class="btn2">글쓰기</a><?}?>
			<span class="search_input">
				<select id="search_mode">
					<option value="title"<?if($PMLIST['MODE'] == 'title'){?> selected<?}?>>제목</option>
					<option value="writer"<?if($PMLIST['MODE'] == 'writer'){?> selected<?}?>>글쓴이</option>
					<option value="content"<?if($PMLIST['MODE'] == 'content'){?> selected<?}?>>내용</option>
				</select><input type="text" id="search_txt"<?if($PMLIST['SEARCH']){?> value="<?=$PMLIST['SEARCH']?>"<?}?> /><a class="search_btn" href="javascript:">검색</a>
			</span>
		</div>
	</div>
</div>

<script>
var category = '<?=$PMLIST['CATEGORY']?$PMLIST['CATEGORY']:"all"?>',
	search = $('#search_txt').val(),
	mode = $('#search_mode option:selected').val(),
	page = <?=$PMLIST['PAGE']?>,
	type = '<?if($PMLIST['TYPE'] == 'best'){?>best<?} else {?>normal<?}?>',
	load_time = 0,
	url = window.location.href;

function cate_best(){
	$.post('/includes/ajax_loads/board.php', {'time':mktime(), 'proc':'cate_best', 'category':category}, function(data){
		$('#cate_best').html(data);

		$('.bart_txt_title').mouseover(function(){
			$('.bart_txt_title').removeClass('on');
			$(this).addClass('on');

			var thumb = $(this).attr('thumb');
			$('.lart .thumb').css({'background-image':'url('+thumb+')','filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(src="+thumb+",sizingMethod='scale')",'-ms-filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+thumb+"', sizingMethod='scale')"}).parent().attr('href',$(this).find('a').attr('href'));
		});
		$('.bart_txt_title:eq(0)').mouseover();
	});
}

function view_list(func){
	load_time++;
	var func = func || null;
	$.post('/includes/ajax_loads/board.php', {'time':mktime(), 'proc':'list', 'type':type, 'category':category, 'search':search, 'mode':mode, 'page':page}, function(data){
		var data_exp = data.split('||^||');
		$('#ajax_contents').html(data_exp[0]);

		var url_data = {'inc':'list', 'type':type, 'category': category, 'search':search, 'mode':mode, 'page':page};
		if(type == 'best'){
			url_data.title = '뒷북 ::: 베스트 게시물';
		} else {
			url_data.title = '뒷북 ::: '+data_exp[1]+' 게시판';
		}
		document.title = url_data.title;

		if(load_time > 1 && history.pushState) {
			history.pushState(url_data, url_data.title, '/?inc='+url_data.inc+'&type='+url_data.type+'&category='+url_data.category+'&search='+url_data.search+'&mode='+url_data.mode+'&page='+url_data.page);
		} else if(load_time > 1) {
			window.location.href = '/?inc='+url_data.inc+'&type='+url_data.type+'&category='+url_data.category+'&search='+url_data.search+'&mode='+url_data.mode+'&page='+url_data.page;
		}

		$('.paging a').click(function(){
			page = $(this).attr('page');
			view_list();
		});

		$('a#write_btn').attr('href','/?inc=write&category='+category);

		cate_best();

		if(func != null) func();
	});
}
$(document).ready(function(){
	if(history.pushState){
		var popped = ('state' in window.history && window.history.state !== null), initialURL = location.href;
		$(window).on('popstate', function() {
			window.location.reload();
		});
	}
	$('.search_btn').click(function(){
		search = $('#search_txt').val();
		mode = $('#search_mode option:selected').val();

		view_list();
	});
	$('#search_txt').keydown(function(e){
		if(e.which == 13){
			$('.search_btn').click();
		}
	});
	$('div.category').click(function(){
		var cate_exp = $(this).attr('id').split('_');
		category = cate_exp[1];

		$('div.category').removeClass('on');
		$(this).addClass('on');

		$('#list_top').text($(this).text()+" 게시물");

		page = 1;
		search = '';
		type = 'normal';
		$('#search_txt').val('');

		view_list();
	});
	$('a#menu_best').click(function(){
		category = 'all';

		$('a#menu_best').addClass('on');
		$('a#menu_community').removeClass('on');

		$('.title2,.list1').show();

		page = 1;
		search = '';
		type = 'best';
		$('#search_txt').val('');

		view_list();
	});
	view_list(function(){
		/* 1:1 문의 열리는 형태
		<?if($PMLIST['IDX'] > 0){?>$('tr[idx=<?=$PMLIST['IDX']?>]:not(.opened)').click().focus();<?}?>*/
	});
});
</script>

<?include($_SERVER['DOCUMENT_ROOT'].'/includes/contents/right_contents.php');?>
