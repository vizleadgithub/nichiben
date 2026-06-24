<?
	//======================================================================//
	//= クローラーかどうか
	//======================================================================//
	function isRobot($UserAgent=''){
		if(!$UserAgent) $UserAgent = $_SERVER['HTTP_USER_AGENT'];
			$robot="/(ICC-Crawler|Teoma|Y!J-BSC|Pluggd\/Nutch|psbot|CazoodleBot|
				Googlebot|Antenna|BlogPeople|AppleWebKitOpenbot|NaverBot|PlantyNet|livedoor|
				msnbot|FlashGet|WebBooster|MIDown|moget|InternetLinkAgent|Wget|InterGet|WebFetch|
				WebCrawler|ArchitextSpider|Scooter|WebAuto|InfoNaviRobot|httpdown|Inetdown|Slurp|
				Spider|^Iron33|^fetch|^PageDown|^BMChecker|^Jerky|^Nutscrape|Baiduspider|TMCrawler)/m";
		if(preg_match($robot,$UserAgent) || ereg($robot,$UserAgent)) {
			return true;
		}else{
			return false;
		}
	}

/*
$this->load->helper('device');
$version = isAlfLearningIpadApp();
if(appVersionWhichBig('2.2.9', '1.2.9') == 'left'){}
*/

	//======================================================================//
	//= ラーニングアプリかどうか
	//======================================================================//
	function isApp(){
		if(isAlfLearningIpadApp()){
			return true;
		}
		if(isAlfLearningIphoneApp()){
			return true;
		}
		if(preg_match('/(CFNetwork)/i', $_SERVER['HTTP_USER_AGENT'])){
			return true;
		}
		return false;
	}

	//======================================================================//
	//= iPadラーニングアプリかどうか
	//= trueなら番号返す
	//======================================================================//
	function isAlfLearningIpadApp(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$ret = false;

		if(preg_match('/iPad/', $userAgent)){	//一番古いアプリがコレしかあたらないので、いつ外すかを考える
			$ret = '1.0.0';
		}
		else{
			return false;
		}

		//Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9B176 ALFLeaning-IPAD 1_0_1
		if(preg_match('/ALFLeaning-IPAD ([0-9]+?)_([0-9]+?)_([0-9]+?)/', $userAgent, $matches)){
			$ret = "{$matches[1]}.{$matches[2]}.{$matches[3]}";
		}

		return $ret;
	}

	//======================================================================//
	//= iPhoneラーニングアプリかどうか
	//= trueなら番号返す
	//======================================================================//
	function isAlfLearningIphoneApp(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$ret = false;

		//Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9B176 ALFLeaning-IPHONE 1_0_1
		if(preg_match('/ALFLeaning-IPHONE ([0-9]+?)_([0-9]+?)_([0-9]+?)/', $userAgent, $matches)){
			$ret = "{$matches[1]}.{$matches[2]}.{$matches[3]}";
		}

		return $ret;
	}

	//======================================================================//
	//= アプリのバージョン番号で、どちらが大きいか
	//= ret = left, right, same
	//======================================================================//
	function appVersionWhichBig($left, $right){
		if($left == $right){
			return 'same';
		}

		$l = explode('.', $left);
		$r = explode('.', $right);

		if(count($l) != count($r)){
			die('app version check error');
		}

		for($i = 0; isset($l[$i]); $i++){
			if($l[$i] > $r[$i]){
				return 'left';
			}
			else if($l[$i] < $r[$i]){
				return 'right';
			}
		}
	}

	//======================================================================//
	//= iphone, ipad以外で対応しているブラウザかをチェック
	//======================================================================//
	function isSupportedBrowser(){
		$ci =& get_instance();

		$browser = array(
			'isKnown'	=> $ci->agent->is_browser(),
			'name'		=> $ci->agent->browser(),
			'version'	=> $ci->agent->version(),
		);

		if(!$browser['isKnown']){
			return false;
		}

		$majorVersion = reset(explode('.', $browser['version']));
		$checker = array(
			'Safari'			=> 533,	//5
			'Chrome'			=> 15,
			'Firefox'			=> 6,
			'Internet Explorer'	=> 9,
		);

		if(!isset($checker[$browser['name']])){
			return false;
		}

		if($majorVersion < $checker[$browser['name']]){
			return false;
		}

		return true;
	}
?>
