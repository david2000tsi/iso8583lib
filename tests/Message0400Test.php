<?php

require_once(__DIR__.'/../messages/Message0400.php');

class Message0400Test extends PHPUnit_Framework_TestCase{
	private $original0400Msg;
	private $generated0400Msg;
	private $iso0400Instance;

	public function testMsg()
	{
		$this->original0400 = "0400F238040000C1100A0000004000000008161008950013090478003000000000000010110712300027000312300007110210066778800000003000000301701012001259102030000000000012345600888844FD00160123456789ABCDEF000000000000000000000000009999999999999999012125125125125";

		$this->iso0400Instance = new Message0400();

		$this->assertTrue($this->iso0400Instance->setField002("1008950013090478"));
		$this->assertTrue($this->iso0400Instance->setField003("003000"));
		$this->assertTrue($this->iso0400Instance->setField004("000000000010"));
		$this->assertTrue($this->iso0400Instance->setField007("1107123000"));
		$this->assertTrue($this->iso0400Instance->setField011("270003"));
		$this->assertTrue($this->iso0400Instance->setField012("123000"));
		$this->assertTrue($this->iso0400Instance->setField013("0711"));
		$this->assertTrue($this->iso0400Instance->setField022("021"));
		$this->assertTrue($this->iso0400Instance->setField041("00667788"));
		$this->assertTrue($this->iso0400Instance->setField042("000000030000003"));
		$this->assertTrue($this->iso0400Instance->setField048("01012001259102030"));
		$this->assertTrue($this->iso0400Instance->setField052("0000000000123456"));
		$this->assertTrue($this->iso0400Instance->setField061("88844FD0"));
		$this->assertTrue($this->iso0400Instance->setField063("0123456789ABCDEF"));
		$this->assertTrue($this->iso0400Instance->setField090("000000000000000000000000009999999999999999"));
		$this->assertTrue($this->iso0400Instance->setField125("125125125125"));

		$this->generated0400Msg = $this->iso0400Instance->getMessage();

		$this->assertTrue($this->generated0400Msg !== false);

		$this->assertEquals($this->original0400, $this->generated0400Msg);

		$this->iso0400Instance = null;

		$this->iso0400Instance = new Message0400($this->generated0400Msg);
		$this->assertTrue($this->iso0400Instance->success());

		$this->assertEquals($this->iso0400Instance->getField002(), "1008950013090478");
		$this->assertEquals($this->iso0400Instance->getField003(), "003000");
		$this->assertEquals($this->iso0400Instance->getField007(), "1107123000");
	}
}
