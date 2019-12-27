<?if(!$MEM['idx']){?><script>alert('로그인 후 이용이 가능합니다.'); history.back();</script>
<?exit;} else if ($_SESSION['mem_user']) { ?>
<script>alert('유저는 글을 작성할 수 없습니다.'); history.back();</script>
<? exit;}
// category가 있을 경우, category 정보 받아두기
if($PMLIST['CATEGORY'] != 'all'){
	$category_qry = mysqli_query($connect,"SELECT * FROM category WHERE code = '".$PMLIST['CATEGORY']."'");
	if(mysqli_num_rows($category_qry) > 0) {
		$category_info = mysqli_fetch_array($category_qry);
	}
}?>
<script src="/includes/module/plupload-2.1.7/js/plupload.full.min.js"></script>
<div class="layout2_l">
	<!--div id="sky">
		<a href="#"><img src="/images/banner2.jpg" alt="" /></a>
	</div-->
	<div class="sub_contents2">
		<div class="title5">
			<img src="/images/icon5.png" alt="" />게시물 작성
		</div>
		<div>
			<div class="myinfo_box1">
				<p>게시글 제목을 입력해 주세요.</p>
				<div class="cate_select">
					<span class="cate_title2">카테고리</span><div class="select3">
						<select id="category">
						<?$where_add = ($MEM['level'] != 99)?" WHERE admin = 'N' AND `show` = '0'":"";
						$category_qry = mysqli_query($connect,"SELECT * FROM category".$where_add);
						if(mysqli_num_rows($category_qry) > 0){
							while($row = mysqli_fetch_array($category_qry)){?>
							<option value="<?=$row['idx']?>" ncon="<?=$row['none_contents']?>" imge="<?=$row['image_essential']?>"<?if($category_info['code'] == $row['code']){?> selected<?}?>><?=$row['title']?></option>
							<?}
						}?>
						</select>
					</div><input type="text" id="title" class="input1" placeholder="제목을 입력해주세요." maxlength=50 style="width:348px;"><span class="myinfo_box2"><b><font id="title_cnt">0</font></b> / 50자</span>
				</div>
				<strong>
					* 허위 정보 및 홍보 또는 광고 등의 글을 게시하시면 해당글은 통보없이 삭제됩니다.<br>
					* 또한 해당 회원은 <b>영구적으로 글쓰기가 제한</b> 될 수 있습니다.
				</strong>
			</div>
			<div class="myinfo_box3">
				<div class="write_box photos_upload_box" style="display:none;">
					<p>사진</p>
					<div class="shop_photos" id='upload_btn'>
						<div class="first_photo photo_up" id="photo_up1" style="background-image:url(/images/image_upload/photo_up1.png);"><img src="/images/image_upload/del.png"></div>
						<div class="more_photo">
							<div id="photo_up2" class="photo_up" style="background-image:url(/images/image_upload/photo_up2.png);"><img src="/images/image_upload/del.png"></div>
							<div id="photo_up3" class="photo_up" style="background-image:url(/images/image_upload/photo_up3.png);"><img src="/images/image_upload/del.png"></div>
							<div id="photo_up4" class="photo_up" style="background-image:url(/images/image_upload/photo_up4.png);"><img src="/images/image_upload/del.png"></div>
							<div id="photo_up5" class="photo_up" style="margin-top:14px; background-image:url(/images/image_upload/photo_up5.png);"><img src="/images/image_upload/del.png"></div>
							<div id="photo_up6" class="photo_up" style="margin-top:14px; background-image:url(/images/image_upload/photo_up6.png);"><img src="/images/image_upload/del.png"></div>
							<div id="photo_up7" class="photo_up" style="margin-top:14px; background-image:url(/images/image_upload/photo_up7.png);"><img src="/images/image_upload/del.png"></div>
						</div>
					</div>
					<label for="image_use" class="photo_in_article"><input type="checkbox" id="image_use" name="image_use"> 본문에 상단 이미지 사용</label>
					<img id="all_photos_del" src="/images/image_upload/all_del.png">
				</div>
				<div class="myinfo_box3_top">
					<b>상세설명</b> <!--input type="radio" id="radio1" name="radio1" /><label for="radio1">HTML로 작성하기</label><input type="radio" id="radio2" name="radio1" /><label for="radio2">에디터로 작성하기</label><a href="#"><img src="images/btn_preview.png" alt="" /></a-->
				</div>
				<div class="myinfo_editor2">
					<script src="/includes/module/ckeditor/ckeditor.js"></script>
					<style>
						/* Style the CKEditor element to look like a textfield */
						.cke_textarea_inline
						{
							padding: 10px;
							height: 309px;
							overflow: auto;

							background: #f8f8f8;
							color: #807d7d;

							border:0;
							-webkit-appearance: textfield;
						}

						.cke_editable.cke_editable_inline.cke_focus
						{
							box-shadow: inset 0px 0px 20px 3px #ddd, inset 0 0 1px #000;
							outline: none;
							background: #f8f8f8;
							color: #807d7d;
							cursor: text;
						}
					</style>
					<!-- webeditor --><textarea id="articlebody" name="articlebody" class="ckeditor" style="width:100%; height:400px; font-family:나눔고딕; font-size:19px;"></textarea><!-- webeditor -->
					<script>//CKEDITOR.inline( 'articlebody' );</script>
					<br/>
					<input type="text" id="source" class="input1" placeholder="출처" maxlength=255 style="width:100%; margin-bottom:5px; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box;">
					<input type="text" id="tag" class="input1" placeholder="태그 : ,로 구분하여 태그를 입력해주세요.(예: 축구, 농구, 야구)" maxlength=255 style="width:100%; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box;">
				</div>
			</div>
			<div class="btn_write_ok" style="border-bottom:0; margin-top:20px; margin-bottom:0;">
				<a id="article_done" href="javascript:">게시글 등록하기</a>
			</div>
		</div>
	</div>
</div>

<div class="layout2_r">
	<div class="rank">
		<div class="rank_title">게시물 옵션</div>
		<div class="write_option" style="margin-top:2em;">
			<div class="title">유행 시기</div>
			<label for="primetime1"><input type="radio" id="primetime1" name="primetime" value="0" checked> 요즘 글</label>
			<label for="primetime2"><input type="radio" id="primetime2" name="primetime" value="1"> 인터넷 개통글</label>
			<label for="primetime3"><input type="radio" id="primetime3" name="primetime" value="2"> 호랑이 담배피던 글</label>
		</div>
		<div class="write_option">
			<div class="title">페이지 게시</div>
			<label for="fp_publish"><input type="checkbox" id="fp_publish" name="fp_publish" checked> 페이스북 페이지에 게시</label>
		</div>
		<div class="write_option">
			<div class="title">페이지</div>
			<div id="page_checkbox"></div>
		</div>
	</div>
</div>

<script>
function page_checkbox(){
	$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'page_checkbox'},function(data){
		$('#page_checkbox').html(data);
	});
}

/* 이미지 업로드 */
var uploader = new plupload.Uploader({
	// General settings
	runtimes : 'html5,html4',
	browse_button : 'upload_btn', // you can pass in id...
	url : '/includes/module/plupload-2.1.7/upload.php',
	chunk_size : '1mb',
	unique_names : true,

	// Resize images on client-side if we can
	//resize : { width : 320, height : 240, quality : 90 },

	filters : {
		max_file_size : '10mb',

		// Specify what files to browse for
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png,jpeg"}
		]
	},
 
	flash_swf_url : '/includes/module/plupload-2.1.7/js/Moxie.swf',
	silverlight_xap_url : '/includes/module/plupload-2.1.7/js/Moxie.xap',
         
	// PreInit events, bound before the internal events
	preinit : {
		Init: function(up, info) {
		},
 
		UploadFile: function(up, file) {
			// You can override settings before the file is uploaded
			// up.setOption('url', 'upload.php?id=' + file.id);
			// up.setOption('multipart_params', {param1 : 'value1', param2 : 'value2'});
		}
	},
 
	// Post init events, bound after the internal events
	init : {
		FilesAdded: function(up, files) {
			// Called when files are added to queue
 
			plupload.each(files, function(file) {
			});

			up.start();
		},

		FileUploaded: function(up, file, info) {
			// Called when file has finished uploading
			//log('[FileUploaded] File:', file, "Info:", info);
			var path_exp = info.response.split('"');
			var path = path_exp[path_exp.length - 2];
			$('.photo_up').each(function(){
				if($(this).data('okay') != true){
					$(this).css('background-image','url('+'/uploaded/temp/'+path+')');
					$(this).data('src',path);
					$(this).data('okay',true);

					$(this).find('img').show().click(function(){
						$(this).hide().parent().css('background-image','url(/images/image_upload/'+$(this).parent().attr('id')+'.png)').data({'src':'','okay':false});
					});
					return false;
				}
			});
		},

		UploadComplete: function(up, files) {
			// Called when all files are either uploaded or failed
		},
 
		Error: function(up, err) {
			// Called when error occurs
			alert("Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "");
			up.refresh(); // Reposition Flash/Silverlight
    	}
	}
});

uploader.init();

$(document).ready(function(){
	$('.layout2_l select').wSelect();

	$('#all_photos_del').click(function(){
		$('.photo_up').each(function(){
			$(this).css('background-image','url(/images/image_upload/'+$(this).attr('id')+'.png)').data({'src':'','okay':false}).find('img').hide();
		});
	});

	$('select#category').change(function(){
		if($(this).find('option:selected').attr('imge') == '1'){
			if(!$('.photos_upload_box').is(":visible")){
				$('.photos_upload_box').show();
				$('.photos_upload_box .moxie-shim').css({'width':'708px', 'height':'301px'});
				$('#image_use').prop('checked',true);
			}
		} else {
			$('.photos_upload_box').hide();
			$('#image_use').prop('checked',false);
		}
	});

	$('select#category').change();

	page_checkbox();
});

CKEDITOR.config.contentsCss = '/css/content_write.css';

CKEDITOR.config.font_defaultLabel = '나눔고딕';
CKEDITOR.config.fontSize_defaultLabel = '19px';

CKEDITOR.replace( 'articlebody',
{
	height: '309px',
	padding: '15px',
	contentsCss: "body {font-family:나눔고딕; font-size:19px;} p {display: block; -webkit-margin-before: 0em; -webkit-margin-after: 0em; -webkit-margin-start: 0px; -webkit-margin-end: 0px; line-height:1.2em;} p * {line-height:1.2em;}"
});

<?$checksum = round(microtime(true) * 1000);?>
var checksum_num = '<?=$checksum?>';

$('#article_done').click(function(){
	var editor = CKEDITOR.instances.articlebody;
	var category_idx = $('select#category').find('option:selected').val();
	var category_title = $('select#category').find('option:selected').text();
	if($('input#title').val() == ''){
		alert('제목을 입력하세요.');
		$('input#title').focus();
		return false;
	}
	if($('select#category').find('option:selected').attr('imge') == '1'){ // 이미지가 꼭 있어야하는 게시판일 경우
		var imglist = '';
		$('.photo_up').each(function(){
			if($(this).data('okay') == true){
				if(imglist != '') imglist += ',';
				imglist += $(this).data('src');
			}
		});
		if(imglist == ''){
			alert('이미지가 없습니다.');
			return false;
		}
	}
	if(editor.getData() == '' && $('select#category').find('option:selected').attr('ncon') != '1'){ // 게시글 내용이 꼭 있어야하는 게시판일 경우
		alert('게시물 내용을 작성해주세요.');
		return false;
	}
	var image_use = 0;
	if($('#image_use').is(':checked')) image_use = 1;
	var checked_page = "";
	$('input[name="facebook_page[]"]:checked').each(function(){
		if(checked_page != "") checked_page += "|";
		checked_page += $(this).val();
	});
	var fp_publish = 0;
	if($('#fp_publish').is(':checked')) fp_publish = 1;
	$.post('/includes/ajax_loads/board.php', {'proc':'insert', 'time':mktime(), 'title':$('#title').val(), 'category_idx':category_idx, 'category_title':category_title, 'image':imglist, 'content':editor.getData(), 'tag':$('#tag').val(), 'source':$('#source').val(), 'primetime':$('input[name=primetime]:checked').val(), 'image_use':image_use, 'checked_page':checked_page, 'fp_publish':fp_publish, 'checksum':checksum_num},function(data){
		var split_data = data.split('||');
		if(split_data[0] == 'nologin'){
			alert('로그인 세션이 만료된 것 같습니다.\n내용을 복사하여 백업하시고 재로그인 후\n다시 시도해보시기 바랍니다.');
		} else if(split_data[0] == 'notinserted'){
			alert('게시물 작성 중 오류가 발생하였습니다.\n내용을 복사하여 백업하시고 새로고침 후\n다시 시도해보시기 바랍니다.');
		} else if(split_data[0] == 'success'){
			alert('작성이 완료되었습니다.');
			location.href='/?inc=article&idx='+split_data[1];
		} else {
			alert(data);
		}
	});
});

$(document).ready(function(){
	$('input#title').keypress(function(){
		$('#title_cnt').text($(this).val().length);
	}).keyup(function(){
		$('#title_cnt').text($(this).val().length);
	});
});
</script>

<div style="clear:both;"></div>

<div style="margin-top:14px; border:1px solid #ccc;">
	<div class="title5">
		<img src="/images/icon5.png" alt="" />페이스북 페이지 관리
	</div>
	<table class="table1" style="background:#fff; border-top:0;">
		<thead>
			<tr>
				<th>페이지명</th>
				<th>코드(*서브도메인)</th>
				<!--th>메타태그</th-->
				<th>앱ID</th>
				<th>앱시크릿</th>
				<th>페이지ID</th>
				<th>오디언스 네트워크</th>
				<th>오디언스 네트워크<br/>리스트</th>
				<th>수정</th>
			</tr>
		</thead>
		<tbody id="fpage_ajax"></tbody>
	</table>
</div>
<script>
function view_list(){
	$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'list'},function(data){
		$('#fpage_ajax').html(data);

		$('.delete_fpage').click(function(){
			if(confirm($(this).attr('title') + " 페이지를 삭제하시겠습니까?")){
				$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'delete','idx':$(this).attr('idx')},function(data){
					if(data == 'success'){
						alert('삭제되었습니다.');
						view_list();
						page_checkbox();
						page_select();
					} else {
						alert(data);
					}
				});
			}
		});
	});
}

$(document).ready(function(){
	view_list();
});
</script>

<div style="margin-top:14px; border:1px solid #ccc;">
	<div class="title5">
		<img src="/images/icon5.png" alt="" />페이스북 페이지 추가
	</div>
	<div id="fpage_form">
		<input id="page_title" type="text" placeholder="페이지 명" />
		<input id="page_code" type="text" placeholder="코드(서브도메인, 코드.backdrum.net)" />
		<!--input id="page_meta" type="text" placeholder="메타태그" /-->
		<input id="page_id" type="text" placeholder="페이지ID" style="border-top:0;" />
		<a href="javascript:" id="page_insert" class="btn2" style="position:relative; float:right; display:inline-block; margin:0; margin-top:5px; top:0; right:1.3%;">추가</a>
	</div>
</div>

<script>
$(document).ready(function(){
	$('#page_insert').click(function(){
		$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'insert','title':$('#page_title').val(),'code':$('#page_code').val(),'page_id':$('#page_id').val()},function(data){ /*'meta':$('#page_meta').val(),*/
			if(data == 'success'){
				alert('추가되었습니다.');
				$('#page_title,#page_code,#page_meta,#page_id').val('');
				view_list();
				page_checkbox();
				page_select();
			} else {
				alert(data);
			}
		});
	});
});
</script>

<div style="margin-top:14px; border:1px solid #ccc;">
	<div class="title5">
		<img src="/images/icon5.png" alt="" />페이스북 페이지 앱 설정
	</div>
	<div id="fpage_form">
		<select id="page_list" style="height:33px; border-bottom:0;"></select><br/>
		<input id="page_app_id" type="text" placeholder="앱 ID" />
		<input id="page_app_secret" type="text" placeholder="앱 시크릿" />
		<input id="anid" type="text" placeholder="Audience Network 노출 위치 ID" style="border-top:0;" />
		<input id="anlid" type="text" placeholder="리스트용 Audience Network 노출 위치 ID" style="border-top:0;" />
		<a href="javascript:" id="page_app" class="btn2" style="position:relative; float:right; display:inline-block; margin:0; margin-top:5px; top:0; right:1.3%;">설정</a>
	</div>
</div>

<script>
function page_select(){
	$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'page_select'},function(data){
		$('#page_list').html(data);
		$('#page_app').off('click');
		$('#page_app').click(function(){
			if($('#page_list').val() == ''){
				alert('페이지를 선택해주세요.');
				return false;
			}

			$.post('/includes/ajax_loads/fpage.php',{'time':mktime(),'proc':'app','idx':$('#page_list').val(),'app_id':$('#page_app_id').val(),'app_secret':$('#page_app_secret').val(),'anid':$('#anid').val(),'anlid':$('#anlid').val()},function(data){
				if(data == 'success'){
					alert('설정되었습니다.');
					$('#page_app_id,#page_app_secret,#anid,#anlid').val('');
					view_list();
				} else {
					alert(data);
				}
			});
		});
	});
}

$(document).ready(function(){
	page_select();
});
</script>
