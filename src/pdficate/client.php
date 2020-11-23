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

	const VERSION = "0.1";

	var $api_key;
	var $options;

	function __construct($options = array()){
		$options += array(
			"api_key" => PDFICATE_API_KEY,

			"format" => "A4",
			"margin_top" => "2cm",
			"margin_left" => "2cm",
			"margin_right" => "2cm",
			"margin_bottom" => "2cm",

			"render_delay" => 10, // ms, the delay before printing to ensure that the page is fully loaded, intended for pages with a JS loading effect and so on
		);

		$this->api_key = $options["api_key"];
		unset($options["api_key"]);

		$this->options = $options;
	}

	function printToPdf($url){
		$params = $this->options;

		$params["url"] = $url;
		$params["api_key"] = $this->api_key;
		$params["format"] = "json";

		$uf = $this->_get("pdf_converters/url_to_pdf",$params);
		if($uf->getStatusCode()!=200){
			$msgs = json_decode($uf->getContent());
			$err_message = is_array($msgs) && isset($msgs[0]) ? $msgs[0] : "conversion to PDF failed";
			throw new \Exception("Pdficate: $err_message");
		}

		$filename = \Files::GetTempFilename("pdficate_").".pdf";
		\Files::WriteToFile($filename,$uf->getContent());

		return $filename;
	}

	/**
	 *
	 *	$url_fetcher = $this->_get("pdf_converters/url_to_pdf",["url" => "http://...", "api_key" => "...", "format" => "json"]);
	 */
	protected function _get($action,$params){
		$url = PDFICATE_API_URL."en/$action/?".http_build_query($params);
		$uf = $this->_getUrlFetcher();
		$uf->fetchContent($url);
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
