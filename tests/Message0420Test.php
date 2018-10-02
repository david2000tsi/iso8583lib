<?php

require_once(__DIR__.'/../messages/Message0420.php');

class Message0420Test extends PHPUnit_Framework_TestCase{
	private $original0420Msg;
	private $generated0420Msg;
	private $iso0420Instance;

	public function testMsg()
	{
		$this->original0420 = "0420F238040000C0000000000040000000001610089500130904780030000000000000101107123000270003123000071132500667788000000030000003000000000000000000000000009999999999999999";

		$this->iso0420Instance = new Message0420();

		$this->iso0420Instance->setField002("1008950013090478");
		$this->iso0420Instance->setField003("003000");
		$this->iso0420Instance->setField004("000000000010");
		$this->iso0420Instance->setField007("1107123000");
		$this->iso0420Instance->setField011("270003");
		$this->iso0420Instance->setField012("123000");
		$this->iso0420Instance->setField013("0711");
		$this->iso0420Instance->setField022("325");
		$this->iso0420Instance->setField041("00667788");
		$this->iso0420Instance->setField042("000000030000003");
		$this->iso0420Instance->setField090("000000000000000000000000009999999999999999");

		$this->generated0420Msg = $this->iso0420Instance->getMessage();

		$this->assertTrue($this->generated0420Msg !== false);

		$this->assertEquals($this->original0420, $this->generated0420Msg);

		$this->iso0420Instance = null;

		$this->iso0420Instance = new Message0420($this->generated0420Msg);
		$this->assertTrue($this->iso0420Instance->success());

		$this->assertEquals($this->iso0420Instance->getField002(), "1008950013090478");
		$this->assertEquals($this->iso0420Instance->getField003(), "003000");
		$this->assertEquals($this->iso0420Instance->getField007(), "1107123000");
	}
}
