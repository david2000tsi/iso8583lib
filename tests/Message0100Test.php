<?php

require_once('../messages/Message0100.php');

class Message0100Test extends PHPUnit_Framework_TestCase{
	private $original0100Msg;
	private $generated0100Msg;
	private $iso0100Instance;

	public function testMsg()
	{
		$this->original0100 = "01006238040000C010081610089500130904780030001107123000270003123000071102200667788000000030000003000000000012345600888844FD0";

		$this->iso0100Instance = new Message0100();

		$this->iso0100Instance->setField002("1008950013090478");
		$this->iso0100Instance->setField003("003000");
		$this->iso0100Instance->setField007("1107123000");
		$this->iso0100Instance->setField011("270003");
		$this->iso0100Instance->setField012("123000");
		$this->iso0100Instance->setField013("0711");
		$this->iso0100Instance->setField022("022");
		$this->iso0100Instance->setField041("00667788");
		$this->iso0100Instance->setField042("000000030000003");
		$this->iso0100Instance->setField052("0000000000123456");
		$this->iso0100Instance->setField061("88844FD0");

		$this->generated0100Msg = $this->iso0100Instance->getMessage();

		$this->assertTrue($this->generated0100Msg !== false);

		$this->assertEquals($this->original0100, $this->generated0100Msg);
	}
}
