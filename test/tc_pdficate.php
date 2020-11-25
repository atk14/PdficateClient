<?php
class TcPdficate extends TcBase {

	function test_getServerAddr(){
		$client = new Pdficate\Client(array("api_url" => "http://localhost/api/"));
		$this->assertEquals("127.0.0.1",$client->getServerAddr());

		$client = new Pdficate\Client(array("api_url" => "http://192.168.1.111/api/"));
		$this->assertEquals("192.168.1.111",$client->getServerAddr());
	}
}
