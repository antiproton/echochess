<?php
class Curl {
	public static $handle=null;

	public static function get($url, $params=null) {
		if(self::$handle===null) {
			self::$handle=curl_init();
		}

		if($params!==null) {
			$arr=[];
			$query="?";

			foreach($params as $key=>$val) {
				$arr[]=$key."=".urlencode($val);
			}

			$query.=implode("&", $arr);
			$url.=$query;
		}

		curl_setopt(self::$handle, CURLOPT_URL, $url);

		return curl_exec(self::$handle);
	}
}
?>