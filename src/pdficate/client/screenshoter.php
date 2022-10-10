<?php

namespace Pdficate\Client;

class Screenshoter extends \Pdficate\Client {

	var $default_options = array(
		"width" => 1024,
		"height" => 768,

		"offset_top" => 0,
		"offset_right" => 0,
		"offset_bottom" => 0,
		"offset_left" => 0,
		
		"delay" => 0, // ms, the delay before printing to ensure that the page is fully loaded, intended for pages with a JS loading effect and so on

		"image_format" => "jpeg", // "png", "jpeg"
	);

	function screenshot($url){
		$params = $this->options;

		$params["url"] = $url;
		$params["api_key"] = $this->api_key;
		$params["format"] = "json";

		$uf = $this->_post("screenshots/create_new",$params);
		if($uf->getStatusCode()!=200){
			$msgs = json_decode($uf->getContent());
			$err_message = is_array($msgs) && isset($msgs[0]) ? $msgs[0] : "screenshoting failed";
			throw new \Exception("Pdficate: $err_message");
		}

		// little sanitization
		$suffix = preg_match('/^[a-z]{3,4}$/',$params["image_format"]) ? $params["image_format"] : "img";

		$filename = \Files::GetTempFilename("pdficate_").".$suffix";
		\Files::WriteToFile($filename,$uf->getContent());

		return $filename;
	}
}
