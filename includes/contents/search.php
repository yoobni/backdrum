<div class="layout2_r">
	<div id="sky">
		<a href="#"><img src="/images/banner2.jpg" alt="" /></a>
	</div>
	<div class="title1">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/img6.jpg" alt="" />검색 결과
	</div>
	<div class="sub_contents2" style="min-height:0; padding-bottom:60px">
		<div class="search_result"></div>
		<div class="paging_layer">
			<div class="paging"></div>
			<div class="search_box">
				<a href="/?inc=write" class="btn2">글쓰기</a>
				<span class="search_input">
					<select id="search_mode">
						<option value="title"<?if($PMLIST['MODE'] == 'title'){?> selected<?}?>>제목</option>
						<option value="writer"<?if($PMLIST['MODE'] == 'writer'){?> selected<?}?>>글쓴이</option>
						<option value="content"<?if($PMLIST['MODE'] == 'content'){?> selected<?}?>>내용</option>
					</select><input type="text" id="search_txt" value="<?=$PMLIST['SEARCH']?>" /><a class="search_btn" href="javascript:">검색</a>
				</span>
			</div>
		</div>
	</div>
</div>
<script>
var search = $('#search_txt').val(),
	mode = $('#search_mode option:selected').val(),
	page = <?=$PMLIST['PAGE']?>,
	load_time = 0,
	url = window.location.href;

function search_view_list(func){
	load_time++;
	var func = func || null;
	$.post('/includes/ajax_loads/board.php', {'time':mktime(), 'proc':'search', 'search':search, 'mode':mode, 'page':page}, function(data){
		var data_exp = data.split('||^||');
		$('.search_result').html(data_exp[0]);
		$('.paging').html(data_exp[1]);

		var url_data = {'inc':'search', 'search':search, 'mode':mode, 'page':page};
		url_data.title = '오타쿠 ::: ['+search+'] 검색';

		document.title = url_data.title;

		if(load_time > 1 && history.pushState) {
			history.pushState(url_data, url_data.title, '/?inc='+url_data.inc+'&search='+url_data.search+'&mode='+url_data.mode+'&page='+url_data.page);
		} else if(load_time > 1) {
			window.location.href = '/?inc='+url_data.inc+'&search='+url_data.search+'&mode='+url_data.mode+'&page='+url_data.page;
		}

		$('.paging a').click(function(){
			page = $(this).attr('page');
			search_view_list();
		});

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

		search_view_list();
	});
	$('#search_txt').keydown(function(e){
		if(e.which == 13){
			$('.search_btn').click();
		}
	});
	search_view_list(function(){
		/* 1:1 문의 열리는 형태
		<?if($PMLIST['IDX'] > 0){?>$('tr[idx=<?=$PMLIST['IDX']?>]:not(.opened)').click().focus();<?}?>*/
	});
});
</script>