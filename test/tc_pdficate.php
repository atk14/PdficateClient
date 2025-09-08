<?php
class TcPdficate extends TcBase {

	function test_getServerAddr(){
		$client = new Pdficate\Client(array("api_url" => "http://localhost/api/"));
		$this->assertEquals("127.0.0.1",$client->getServerAddr());

		$client = new Pdficate\Client(array("api_url" => "http://192.168.1.111/api/"));
		$this->assertEquals("192.168.1.111",$client->getServerAddr());
	}

	function test__cleanupBooleans(){
		$client = new Pdficate\Client();

		$params = ["format" => "A4", "print_background" => true];
		$this->assertEquals(["format" => "A4", "print_background" => "on"],$client->_cleanupBooleans($params));

		$params = ["format" => "A4", "print_background" => false];
		$this->assertEquals(["format" => "A4"],$client->_cleanupBooleans($params));
	}
}
