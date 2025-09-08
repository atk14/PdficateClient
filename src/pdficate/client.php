<?php

namespace Pdficate;

/**
 * A client for Pdficate.com - HTML to PDF convertor
 *
 *
 *	$pdficate = new \Pdficate\Client();
 *	$filename = $pdficate->printToPdf("https://www.atk14.net/");
 */

defined("PDFICATE_API_KEY") 							|| define("PDFICATE_API_KEY","some_secret_key");
defined("PDFICATE_API_URL") 							|| define("PDFICATE_API_URL","https://pdficate.com/api/");

class Client {

	const VERSION = "0.1.2";

	var $api_key;
	var $api_url;
	var $options;

	var $default_options = array(
		"page_size" => "A4",

		"margin_top" => "2cm",
		"margin_right" => "2cm",
		"margin_bottom" => "2cm",
		"margin_left" => "2cm",
		
		"print_background" => false, // true, false, "on", "off", "true", "false"...

		"delay" => 0, // ms, the delay before printing to ensure that the page is fully loaded, intended for pages with a JS loading effect and so on
	);

	function __construct($options = array()){
		$options += $this->default_options;
		$options += array(
			"api_key" => PDFICATE_API_KEY,
			"api_url" => PDFICATE_API_URL,
		);

		$this->api_key = $options["api_key"];
		unset($options["api_key"]);

		$this->api_url = $options["api_url"];
		unset($options["api_url"]);

		$this->options = $options;
	}

	function printToPdf($url){
		$params = $this->options;

		$params["url"] = $url;
		$params["api_key"] = $this->api_key;
		$params["format"] = "json";

		$params = $this->_cleanupBooleans($params);

		$uf = $this->_post("pdf_printings/create_new",$params);
		if($uf->getStatusCode()!=200){
			$msgs = json_decode($uf->getContent());
			$err_message = is_array($msgs) && isset($msgs[0]) ? $msgs[0] : "conversion to PDF failed";
			throw new \Exception("Pdficate: $err_message");
		}

		$filename = \Files::GetTempFilename("pdficate_").".pdf";
		\Files::WriteToFile($filename,$uf->getContent());

		return $filename;
	}

	function _cleanupBooleans($params){
		foreach([
				"print_background",
		] as $k){
			// string to bool
			if(is_string($params[$k])){
				$params[$k] = \Strin4::ToObject($params[$k])->toBoolean();
			}

			// bool to "on" or nothing
			if($params[$k]){
				$params[$k] = "on";
			}else{
				unset($params[$k]);
			}
		}

		return $params;
	}

	/**
	 *
	 *	echo $client->getServerAddr(); // 89.187.145.110
	 */
	function getServerAddr(){
		if(preg_match('/https?:\/\/([^\/]+?)(:\d+|)\//',$this->api_url,$matches)){
			return gethostbyname($matches[1]);
		}
	}

	/**
	 *
	 *	$url_fetcher = $this->_get("pdf_converters/url_to_pdf",["url" => "http://...", "api_key" => "...", "format" => "json"]);
	 */
	protected function _get($action,$params){
		$url = $this->api_url."en/$action/?".http_build_query($params);
		$uf = $this->_getUrlFetcher();
		$uf->fetchContent($url);
		return $uf;
	}

	protected function _post($action,$params){
		$url = $this->api_url."en/$action/";
		$uf = $this->_getUrlFetcher($url);
		$uf->post($params);
		return $uf;
	}

	/**
	 *
	 * $uf = $this->_getUrlFetcher("pdfs/create_new");
	 */
	protected function _getUrlFetcher($url = null){
		$uf = new \UrlFetcher($url,array(
			"user_agent" => "Pdficate/".self::VERSION." UrlFetcher/".\UrlFetcher::VERSION,
		));
		return $uf;
	}

	protected function _getAuthToken(){
		$time = time();
		$api_key = $this->api_key;
		$t = $time - ($time % (60 * 10)); // new auth_token every 10 minutes
		$ar = explode(".",$api_key);
		$id = (int)$ar[0];
		return $id.".".hash("sha256",$api_key.$t);
	}
}
