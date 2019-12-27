<?php
// FUNCTION TO MUNG THE XML
function mungXML($xml)
{
    // A REGULAR EXPRESSION TO MUNG THE XML
	$rgx
	= '#'           // REGEX DELIMITER
	. '('           // GROUP PATTERN 1
	. '\<'          // LOCATE A LEFT WICKET
	. '/{0,1}'      // MAYBE FOLLOWED BY A SLASH
	. '.*?'         // ANYTHING OR NOTHING
	. ')'           // END GROUP PATTERN
	. '('           // GROUP PATTERN 2
	. ':{1}'        // A COLON (EXACTLY ONE)
	. ')'           // END GROUP PATTERN
	. '#'           // REGEX DELIMITER
	;
	// INSERT THE UNDERSCORE INTO THE TAG NAME
	$rep
	= '$1'          // BACKREFERENCE TO GROUP 1
	. '_'           // LITERAL UNDERSCORE IN PLACE OF GROUP 2
	;
	// PERFORM THE REPLACEMENT
	$xml = preg_replace($rgx, $rep, $xml);

	// FIX THE URLS
	$xml = str_replace('https_//', 'https://', $xml);
	return $xml;
}

//Data, connection, auth
$dataFromTheForm = $_POST['fieldName']; // request data from the form
$soapUrl = "https://sms.xnoti.com/movie/TicketingService.svc"; // asmx URL of WSDL
/*$soapUser = "username";  //  username
$soapPassword = "password"; // password*/

// xml post structure

$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
	<s:Body>
		<GetMovie xmlns="http://tempuri.org/">
			<fi_corp_key>06FE027E-6B45-419B-A2EC-D1ADF400DDBB</fi_corp_key>
			<user_id>2480</user_id>
			<result/>
		</GetMovie>
	</s:Body>
</s:Envelope>';   // data from the form, e.g. some ID number

$headers = array(
	"Content-Type: text/xml; charset=utf-8",
	"Accept: text/xml",
	"Cache-Control: no-cache",
	"Pragma: no-cache",
	"SOAPAction: http://tempuri.org/ITicketingService/GetMovie", 
	"Content-length: ".strlen($xml_post_string),
	"Expect: 100-continue",
	"Accept-Encoding: gzip, deflate",
	"Host: sms.xnoti.com"
); //SOAPAction: your op URL

$url = $soapUrl;

// PHP cURL  for https connection with auth
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// converting
$response = curl_exec($ch); 
curl_close($ch);

/*echo $response;

$xmlResp = simplexml_load_string($response);
$jsonResp = json_encode($xmlResp);
$arrResp = json_decode($jsonResp);

foreach($arrResp as $k=>$v) {
    print_r($v);
}*/

// TRANSFORM THE XML
$new = mungxml($response);
//echo htmlentities($new);

// MAKE AN OBJECT
$obj = @SimpleXML_Load_String($new);
//var_dump($obj);
//print_r($obj);

$mdata = $obj->s_Body->GetMovieResponse->GetMovieResult->a_ticketing_movie;
$all_count = count($mdata);
echo $all_count;?>||^||	<div class="real_content">
		<div class="movie_sel" style="width:<?=240*$all_count?>px;">
<?for($i=0;$i<count($mdata);$i++){?>
			<div class="img" idx="<?=$mdata[$i]->a_moviemasterid?>"><img src="http://image.ecomovie.co.kr/handler/image.ashx?key=3&h=316&w=220&file=<?=$mdata[$i]->a_moviemasterid?>_poster.jpg"><div class="movie_title"><img src="/images/main/<?=$mdata[$i]->a_grades?>circle.png" class="circle"><?=$mdata[$i]->a_name?></div></div>
<?}?>
		</div>
	</div>||^||			<img id="poster_left" src="/images/main/thumb_left_arrow.png">
			<div class="thumbs">
				<div class="thumb_scroll" style="width:<?=61*$all_count?>px;">
<?for($i=0;$i<count($mdata);$i++){?>
					<img src="http://image.ecomovie.co.kr/handler/image.ashx?key=3&w=60&h=85&file=<?=$mdata[$i]->a_moviemasterid?>_poster.jpg" order="<?=$i?>">
<?}?>
				</div>
			</div>
			<img id="poster_right" src="/images/main/thumb_right_arrow.png">