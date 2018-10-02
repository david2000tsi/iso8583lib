<?php

require_once(__DIR__.'/../messages/Message0202.php');

class Message0202Test extends PHPUnit_Framework_TestCase{
	private $original0202Msg;
	private $generated0202Msg;
	private $iso0202Instance;

	public function testMsg()
	{
		$this->original0202 = "0202B238000002C0000400000000000000020030000000000000101107123000270003123000071100006677880000000300000030160000000000123456006787878";

		$this->iso0202Instance = new Message0202();

		$this->iso0202Instance->setField003("003000");
		$this->iso0202Instance->setField004("000000000010");
		$this->iso0202Instance->setField007("1107123000");
		$this->iso0202Instance->setField011("270003");
		$this->iso0202Instance->setField012("123000");
		$this->iso0202Instance->setField013("0711");
		$this->iso0202Instance->setField039("00");
		$this->iso0202Instance->setField041("00667788");
		$this->iso0202Instance->setField042("000000030000003");
		$this->iso0202Instance->setField062("0000000000123456");
		$this->iso0202Instance->setField127("787878");

		$this->generated0202Msg = $this->iso0202Instance->getMessage();

		$this->assertTrue($this->generated0202Msg !== false);

		$this->assertEquals($this->original0202, $this->generated0202Msg);

		$this->iso0202Instance = null;

		$this->iso0202Instance = new Message0202($this->generated0202Msg);
		$this->assertTrue($this->iso0202Instance->success());

		$this->assertEquals($this->iso0202Instance->getField003(), "003000");
		$this->assertEquals($this->iso0202Instance->getField004(), "000000000010");
		$this->assertEquals($this->iso0202Instance->getField007(), "1107123000");
	}
}
