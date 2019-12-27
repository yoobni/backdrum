<?
define(PMSYSTEM_CHECK,"!#DSS@#!SAADTUUF&&%&*");

exit;

require_once('vendor/autoload.php');

/* 시스템 함수 호출 */
include($_SERVER['DOCUMENT_ROOT'].'/includes/system/system.php');

if($PMLIST['IDX']){
	$sql = mysqli_query($connect,"SELECT * FROM facebook_page WHERE idx = ".$PMLIST['IDX']);
	if(mysqli_num_rows($sql) > 0){
		$page_info = mysqli_fetch_array($sql);
		$fb = new Facebook\Facebook([
		  'app_id' => $page_info['app_id'],
		  'app_secret' => $page_info['app_secret'],
		  'default_graph_version' => 'v2.9',
		]);

		$helper = $fb->getRedirectLoginHelper();

		$permissions = ['publish_actions','publish_pages','manage_pages']; // optional

		try {
			if (isset($_SESSION['fat_'.$PMLIST['IDX']])) {
				$accessToken = $_SESSION['fat_'.$PMLIST['IDX']];
			} else {
				$accessToken = $helper->getAccessToken();
			}
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$msg = "로그인 오류 : Graph returned an error (".$e->getMessage().")";
			header("Location: http://admin.backdrum.net/facebook/login_receive.php?msg=".urlencode($msg));
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$msg = "로그인 오류 : Facebook SDK returned an error (".$e->getMessage().")";
			header("Location: http://admin.backdrum.net/facebook/login_receive.php?msg=".urlencode($msg));
			exit;
		}

		if (isset($accessToken)) {
			if (isset($_SESSION['fat_'.$PMLIST['IDX']])) {
				$fb->setDefaultAccessToken($_SESSION['fat_'.$PMLIST['IDX']]);
			} else {
				// getting short-lived access token
				$_SESSION['fat_'.$PMLIST['IDX']] = (string) $accessToken;

				// OAuth 2.0 client handler
				$oAuth2Client = $fb->getOAuth2Client();

				// Exchanges a short-lived access token for a long-lived one
				$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['fat_'.$PMLIST['IDX']]);

				$_SESSION['fat_'.$PMLIST['IDX']] = (string) $longLivedAccessToken;

				// setting default access token to be used in script
				$fb->setDefaultAccessToken($_SESSION['fat_'.$PMLIST['IDX']]);
			}

			// getting basic info about user
			try {
				$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
				$profile = $profile_request->getGraphNode()->asArray();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				$msg = "로그인 오류 : Graph returned an error (".$e->getMessage().")";
				header("Location: http://admin.backdrum.net/facebook/login_receive.php?msg=".urlencode($msg));
				session_destroy();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				$msg = "로그인 오류 : Facebook SDK returned an error (".$e->getMessage().")";
				header("Location: http://admin.backdrum.net/facebook/login_receive.php?msg=".urlencode($msg));
				exit;
			}?>
			<form name="access_token" method="post">
				<input type="text" name="idx" value="<?=$PMLIST['IDX']?>">
				<input type="text" name="token" value="<?=$accessToken?>">
			</form>
			<script>
				var HForm = document.payment;
				HForm.action = "http://admin.backdrum.net/facebook/login_receive.php?time=<?=mktime()?>";
				document.charset = 'utf-8';

				HForm.submit();
			</script>
			<?// Now you can redirect to another page and use the access token from $_SESSION['fat_'.$PMLIST['IDX']]
		} else {
			// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
			$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['HTTP_HOST'].'/facebook/facebook_login.php?time='.mktime().'&idx='.$PMLIST['IDX'], $permissions);
			header("Location: ".$loginUrl);
		}
	} else {
		$msg = "로그인 오류 : 해당 페이지 정보가 없습니다.";
		header("Location: http://admin.backdrum.net/facebook/login_receive.php?msg=".urlencode($msg));
	}
}
?>