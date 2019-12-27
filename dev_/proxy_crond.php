<?
set_time_limit(0);
date_default_timezone_set('Asia/Seoul');

//$proxy = '192.3.1.96:3128';

class Curl_class {
	var $url = '';                // 접속 URL
	var $cookie = 'cookie.txt';  // 쿠키파일 입니다.
	var $post = 0;                // post 값 여부
	var $post_ok = '';				// post로 보낼지 여부
	var $parms = '';                // 전송할 파라미터
	var $parms_type = '';                // 전송할 파라미터 타입
	var $recive = '';                // 결과값 저장
	var $return = 1;                // Curl 옵션
	var $timeout = 30;                // Curl 옵션
	var $addopt = '';                  // 추가 Curl 옵션
	var $proxy = '';
	var $referer = '';

	function action(){
		/* 배열로 저장된 파리미터 값을 Get 타입으로 변경 해줌 */
		if($this->parms_type == 'get'){
			if(sizeof($this->parms) > 0){
				$datas = '';
				foreach ($this->parms as $obj=>$val){
					$datas .= $obj.'='.$val.'&';
				}
				$this->parms = substr($datas,0,-1);
			}
		}
		if($this->post_ok != 1 && $this->parms){
			$this->url = $this->url."?".$this->parms;
		}

		// Curl 실행
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL,$this->url);
		curl_setopt ($ch, CURLOPT_POST, $this->post);
		if($this->proxy){
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
			//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);

			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		if($this->post_ok == 1){
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $this->parms);
		}
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $this->cookie);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $this->cookie);
		curl_setopt ($ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, $this->return);
		if($this->referer){
			curl_setopt ($ch, CURLOPT_REFERER, $this->referer);
		}

		// 추가 Curl옵션이 있을경우 이를 적용 시켜줌
		if($this->addopt) curl_setopt_array($ch, $this->addopt);
		$this->recive = curl_exec ($ch);

		curl_close($ch);
	}
}

//위 로그인된 쿠키 이용하기
$curl = new Curl_class();

//긁어 올 페이지 
//$curl->url = "http://spys.ru/en/http-proxy-list/";
$curl->url = "http://spys.ru/free-proxy-list/US/";

//페이지 이동
$curl->action();

$receive = $curl->recive;

$explode = explode('<tr><td>&nbsp;</td></tr></td></tr></table><script type="text/javascript">eval(',$receive);
$explode = explode('</script>',$explode[1]);

$data = substr(trim($explode[0]),0,-1);

//echo 'node "D:\\65. Otaku\\dev\\app.js" "'.str_replace('return p}','return p} ar',str_replace('(p,r,o',' ar(p,r,o',$data)).'"<br/>';
$out = shell_exec('node app.js "'.str_replace('return p}','return p} ar',str_replace('(p,r,o',' ar(p,r,o',$data)).'"').PHP_EOL;

$a = explode(';',trim($out));
for($i=0;$i<count($a)-1;$i++){
	$b[$i] = explode('=',$a[$i]);
	$b[$i][0] = str_replace(" ","",$b[$i][0]);
}

for($i=0;$i<count($a)-1;$i++){
	for($j=0;$j<count($a)-1;$j++){
		if(strpos($b[$i][1],$b[$j][0])){
			$b[$i][1] = strtonum(str_replace($b[$j][0],$b[$j][1],$b[$i][1]));
		}
	}
}

$explode = explode('<tr class=spy',$receive);
for($i=3;$i<count($explode)-1;$i++){
	$ip_adr = explode('<font class=spy',$explode[$i]);

	if(strpos($ip_adr[4],'</a>')){
		$ip_type = 'HTTP';
	}else{
		$ip_type = 'HTTPS';
	}
	$proxy_ip['type'][] = $ip_type;

	$ip_port = explode('))',$ip_adr[3]);
	$ip_port = explode('+',$ip_port[0]);
	$temp_port = "";
	for($k=1;$k<count($ip_port);$k++){
		$ip_port[$k] = str_replace("(","",$ip_port[$k]);
		$ip_port[$k] = str_replace(")","",$ip_port[$k]);
		$ip_port[$k] = str_replace(" ","",$ip_port[$k]);

		for($j=0;$j<count($a)-1;$j++){
			$ip_port[$k] = str_replace($b[$j][0],$b[$j][1],$ip_port[$k]);
		}

		$ip_port[$k] = strtonum($ip_port[$k]);
		$temp_port .= $ip_port[$k];
	}

	$proxy_ip['port'][] = $temp_port;

	$ip_adr = explode('>',$ip_adr[2]);
	$ip_adr = explode('<script',$ip_adr[1]);
	$ip_adr = $ip_adr[0];

	$proxy_ip['ip'][] = $ip_adr;
}

for($i=0;$i<count($proxy_ip['ip']);$i++){
	if($proxy_ip['port'][$i] < 99999){
		echo "INSERT INTO proxy SET ip = '".$proxy_ip['ip'][$i]."', port = '".$proxy_ip['port'][$i]."', type = '".$proxy_ip['type'][$i]."', regdate = '".date("Y-m-d")."'<br/>";
	}
}

function strtonum($str) {
	$str = preg_replace('`([^+\-*=/\(\)\d\^<>&|\.]*)`','',$str);
	$str = explode('^',$str);
	if(count($str) > 1){
		
	}
	if(empty($str)){
		$str = '0';
	} else {
		eval("\$str = $str;"); 
	}
	return $str; 
}
?>