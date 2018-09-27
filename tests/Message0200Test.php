<?php

require_once('../messages/Message0200.php');

class Message0200Test extends PHPUnit_Framework_TestCase{
	private $original0200Msg;
	private $generated0200Msg;
	private $iso0200Instance;

	public function testMsg()
	{
		$this->original0200 = "0200723C040000C1100E1610089500130904780030000000000000101107123000270003123000071112990210066778800000003000000301701012001259102030000000000012345600888844FD001662006200620062000166363636363636363";

		$this->iso0200Instance = new Message0200();

		$this->iso0200Instance->setField002("1008950013090478");
		$this->iso0200Instance->setField003("003000");
		$this->iso0200Instance->setField004("000000000010");
		$this->iso0200Instance->setField007("1107123000");
		$this->iso0200Instance->setField011("270003");
		$this->iso0200Instance->setField012("123000");
		$this->iso0200Instance->setField013("0711");
		$this->iso0200Instance->setField014("1299");
		$this->iso0200Instance->setField022("021");
		$this->iso0200Instance->setField041("00667788");
		$this->iso0200Instance->setField042("000000030000003");
		$this->iso0200Instance->setField048("01012001259102030");
		$this->iso0200Instance->setField052("0000000000123456");
		$this->iso0200Instance->setField061("88844FD0");
		$this->iso0200Instance->setField062("6200620062006200");
		$this->iso0200Instance->setField063("6363636363636363");

		$this->generated0200Msg = $this->iso0200Instance->getMessage();

		$this->assertTrue($this->generated0200Msg !== false);

		$this->assertEquals($this->original0200, $this->generated0200Msg);
	}
}
