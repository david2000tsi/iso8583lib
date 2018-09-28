<?php

require_once('../messages/Message0600.php');

class Message0600Test extends PHPUnit_Framework_TestCase{
	private $original0600Msg;
	private $generated0600Msg;
	private $iso0600Instance;

	public function testMsg()
	{
		$this->original0600 = "0600B238000000C0000000000000000000020030004536324534651107123000456346123000215041414141243654364564564009012345678";

		$this->iso0600Instance = new Message0600();

		$this->iso0600Instance->setField003("003000");
		$this->iso0600Instance->setField004("453632453465");
		$this->iso0600Instance->setField007("1107123000");
		$this->iso0600Instance->setField011("456346");
		$this->iso0600Instance->setField012("123000");
		$this->iso0600Instance->setField013("2150");
		$this->iso0600Instance->setField041("41414141");
		$this->iso0600Instance->setField042("243654364564564");
		$this->iso0600Instance->setField127("012345678");

		$this->generated0600Msg = $this->iso0600Instance->getMessage();

		$this->assertTrue($this->generated0600Msg !== false);

		$this->assertEquals($this->original0600, $this->generated0600Msg);
	}
}
