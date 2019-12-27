/* 공용 */
function leadingZeros(n, digits) {
	var zero = '';
	n = n.toString();

	if (n.length < digits) {
		for (var i = 0; i < digits - n.length; i++) zero += '0';
	}
	return zero + n;
}

function FlexMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName + 'IE'];
	} else {
		return document[movieName];
	}
}

function bookmarksite(title, url) { //즐겨찾기 추가
	// Internet Explorer
	try {
		window.external.AddFavorite(url, title); 
	} catch(e) {
		// Google Chrome
		if(window.chrome){
			alert("Ctrl+D키를 누르시면 즐겨찾기에 추가하실 수 있습니다.");
		}
		// Firefox
		else if (window.sidebar) // firefox 
		{
			window.sidebar.addPanel(title, url, ""); 
		}
		// Opera
		else if(window.opera && window.print)
		{ // opera 
			var elem = document.createElement('a'); 
			elem.setAttribute('href',url); 
			elem.setAttribute('title',title); 
			elem.setAttribute('rel','sidebar'); 
			elem.click(); 
		}
	}
}

/* 일반 기능 */
$(document).ready(function(){
	$('#main_search').click(function(){
		if(typeof search_view_list == 'function'){
			$('#search_txt').val($('#main_search_txt').val());
			$('.search_btn').click();
		} else {
			window.location.href = '/?inc=search&search='+$('#main_search_txt').val();
		}
	});
	$('#main_search_txt').keydown(function(e){
		if(e.which == 13){
			$('#main_search').click();
		}
	});
	$('.realtime_txt').click(function(){
		$('#main_search_txt').val($(this).attr('kwd'));
		$('#main_search').click();
	});
});

/* 커뮤니티 기능 */
/* 서버와 시간 차이 */

var sv_cl_int = 0;

function date_mk(utme){
	var msecPerMinute = 1000 * 60;
	var msecPerHour = msecPerMinute * 60;
	var msecPerDay = msecPerHour * 24;
	var msecPerMonth = msecPerDay * 30;
	var msecPerYear = msecPerMonth * 12;

	var nowDate = new Date();
	var theDate = new Date((utme) * 1000 + sv_cl_int * 1000);
	var dateMsec = theDate.getTime();
	var nowMsec = nowDate.getTime();
	var interval = nowMsec - dateMsec;

	var years = Math.floor(interval / msecPerYear );
	interval = interval - (years * msecPerYear );

	var months = Math.floor(interval / msecPerMonth );
	interval = interval - (months * msecPerMonth );

	var days = Math.floor(interval / msecPerDay );
	interval = interval - (days * msecPerDay );

	var hours = Math.floor(interval / msecPerHour );
	interval = interval - (hours * msecPerHour );

	var minutes = Math.floor(interval / msecPerMinute );
	interval = interval - (minutes * msecPerMinute );

	var seconds = Math.floor(interval / 1000 );

	var years_txt = '';
	var months_txt = '';
	var days_txt = '';
	var hours_txt = '';
	var minutes_txt = '';
	var seconds_txt = '';

	if(years != 0){
		years_txt = years + "년 ";
	}
	if(years == 0 && (months != 0 || years_txt != '')){
		months_txt = months + "달 ";
	}
	if((years == 0 && months == 0) && (days != 0 || months_txt != '')){
		days_txt = days + "일 ";
	}
	if((years == 0 && months == 0 && days == 0) && (hours != 0 || days_txt != '')){
		hours_txt = hours + "시간 ";
	}
	if((years == 0 && months == 0 && days == 0 && hours == 0) && (minutes != 0 || hours_txt != '')){
		minutes_txt = minutes + "분 ";
	}
	if(years == 0 && months == 0 && days == 0 && hours == 0 && minutes == 0){
		seconds_txt = Math.floor(seconds/5)*5 + "초 ";
	}

	return days_txt + hours_txt + minutes_txt + seconds_txt + "전";
}

var comment_dif;

function time_dif_view() {
	clearTimeout(comment_dif);
	$("span[utime]").each(function(){
		$(this).html(date_mk($(this).attr('utime')));
	});
	comment_dif = setTimeout("time_dif_view()", 1000);
}

$(document).ready(function(){
	var before_get = mktime();
	$.post('/includes/ajax_loads/server_time.php',{'time':mktime()},function(data){
		var server_time = parseInt(data);
		sv_cl_int = before_get - server_time;

		time_dif_view();
	});
});

/* 댓글 */

var page = 1;
function get_comment(idx,type,page,func){
	var page = page || 1;
	var func = func || null;
	//$('.comment_list').load('/includes/ajax_loads/board.php',{'time':mktime(), 'proc':'read_com', 'type':type, 'idx':idx});
	$.post('/includes/ajax_loads/board.php',{'time':mktime(), 'proc':'read_com', 'type':type, 'idx':idx},function(data){
		var data_exp = data.split('||');
		$('#comment_list').html(data_exp[0]);
		$('#comment_count').text(number_format(parseInt(data_exp[1])));
		if(typeof func == 'function') func();
	});
	var before_get = mktime();
	$.post('/includes/ajax_loads/server_time.php', {'time': mktime()}, function(data){
		sv_cl_int = before_get - data;
	});
}

function insert_comment(idx,type,text_obj) {
	if(text_obj.val == ''){
		alert('댓글 내용을 작성해주세요.');
		text_obj.focus();
	}
	$.post('/includes/ajax_loads/board.php',{'time':mktime(), 'proc':'write_com', 'type':type, 'idx':idx, 'text':text_obj.val(), 'keylog':$('#keylog').val()},function(data){
		data_exp = data.split("||");
		if(data_exp[0] == 'nologin'){
			alert('로그인 후 이용해주세요.');
		} else if(data_exp[0] == 'success'){
			$('#keylog').val(data_exp[1]);
			text_obj.val('');
			$('#cmt_txt_cnt_'+text_obj.attr('idx')).text('0');
			get_comment(idx,type,page);
		} else {
			alert(data);
		}
	});
}

function del_comment(idx,article_idx,type) {
	$.post('/includes/ajax_loads/board.php',{'time':mktime(), 'proc':'delete_com', 'idx':idx, 'article_idx':article_idx, 'type':type},function(data){
		data_exp = data.split("||");
		if(data_exp[0] == 'nologin'){
			alert('로그인 후 이용해주세요.');
		} else if(data_exp[0] == 'success'){
			$('#keylog').val(data_exp[1]);
			get_comment(article_idx,type,page);
		} else {
			alert(data);
		}
	});
}

function reco_com(idx) {
	$.post('/includes/ajax_loads/board.php',{'idx':idx,'proc':'reco_com','time': mktime()},function(data){
		data_exp = data.split("||");
		if(data_exp[0] == 'nologin'){
			alert('로그인 후 이용해주세요.');
		} else if(data_exp[0] == 'success'){
			$('#keylog').val(data_exp[1]);
			get_comment(data_exp[2],data_exp[3],page);
			$('span#com_'+idx).focus();
		} else {
			alert(data);
		}
	});
}

/* otaku common */

$(function(){
	var cssTop = parseInt($("#sky").css("top"));
	$(window).scroll(function(){
		var position = $(window).scrollTop();
		$("#sky").stop().animate({"top":position+cssTop+"px"},500);
	});
	$(".join_box2_title a").click(function() {
		$(this).parent().parent().toggleClass("on");
	});
});

/* 롤링 */
function fn_article3(containerID, buttonID, autoStart){
	var $element = $('#'+containerID).find('.notice-list');
	var $prev = $('#'+buttonID).find('.prev');
	var $next = $('#'+buttonID).find('.next');
	var $play = $('#'+containerID).find('.control > a.play');
	var $stop = $('#'+containerID).find('.control > a.stop');
	var autoPlay = autoStart;
	var auto = null;
	var speed = 2000;
	var timer = null;

	var move = $element.children().outerHeight();
	var first = false;
	var lastChild;

	lastChild = $element.children().eq(-1).clone(true);
	lastChild.prependTo($element);
	$element.children().eq(-1).remove();

	if($element.children().length==1){
		$element.css('top','0px');
	}else{
		$element.css('top','-'+move+'px');
	}

	if(autoPlay){
		timer = setInterval(moveNextSlide, speed);
		//$play.addClass('on').text('▶');
		auto = true;
	}else{
		$play.hide();
		$stop.hide();
	}

	$element.find('>li').bind({
		'mouseenter': function(){
			if(auto){
				clearInterval(timer);
			}
		},
		'mouseleave': function(){
			if(auto){
				timer = setInterval(moveNextSlide, speed);
			}
		}
	});

	function movePrevSlide(){
		$element.each(function(idx){
			if(!first){
				$element.eq(idx).animate({'top': '0px'},'normal',function(){
					lastChild = $(this).children().eq(-1).clone(true);
					lastChild.prependTo($element.eq(idx));
					$(this).children().eq(-1).remove();
					$(this).css('top','-'+move+'px');
				});
				first = true;
				return false;
			}

			$element.eq(idx).animate({'top': '0px'},'normal',function(){
				lastChild = $(this).children().filter(':last-child').clone(true);
				lastChild.prependTo($element.eq(idx));
				$(this).children().filter(':last-child').remove();
				$(this).css('top','-'+move+'px');
			});
		});
	}

	function moveNextSlide(){
		$element.each(function(idx){

			var firstChild = $element.children().filter(':first-child').clone(true);
			firstChild.appendTo($element.eq(idx));
			$element.children().filter(':first-child').remove();
			$element.css('top','0px');

			$element.eq(idx).animate({'top':'-'+move+'px'},'normal');

		});
	}
}