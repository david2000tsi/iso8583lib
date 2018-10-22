<?php

require_once(__DIR__.'/../vendor/autoload.php');

use ISO8583LIB\Messages\Message0110;

class Message0110Test extends PHPUnit_Framework_TestCase{
	private $original0110Msg;
	private $generated0110Msg;
	private $iso0110Instance;

	public function testMsg()
	{
		$this->original0110 = "0110B238000002C00006000000000000000200300032432432423411071230002700031230000711390066778800000003000000301662006200620062000160000000000123456009127127999";

		$this->iso0110Instance = new Message0110();

		$this->assertTrue($this->iso0110Instance->setField003("003000"));
		$this->assertTrue($this->iso0110Instance->setField004("324324324234"));
		$this->assertTrue($this->iso0110Instance->setField007("1107123000"));
		$this->assertTrue($this->iso0110Instance->setField011("270003"));
		$this->assertTrue($this->iso0110Instance->setField012("123000"));
		$this->assertTrue($this->iso0110Instance->setField013("0711"));
		$this->assertTrue($this->iso0110Instance->setField039("39"));
		$this->assertTrue($this->iso0110Instance->setField041("00667788"));
		$this->assertTrue($this->iso0110Instance->setField042("000000030000003"));
		$this->assertTrue($this->iso0110Instance->setField062("6200620062006200"));
		$this->assertTrue($this->iso0110Instance->setField063("0000000000123456"));
		$this->assertTrue($this->iso0110Instance->setField127("127127999"));

		$this->generated0110Msg = $this->iso0110Instance->getMessage();

		$this->assertTrue($this->generated0110Msg !== false);

		$this->assertEquals($this->original0110, $this->generated0110Msg);

		$this->iso0110Instance = null;

		$this->iso0110Instance = new Message0110($this->generated0110Msg);
		$this->assertTrue($this->iso0110Instance->success());

		$this->assertEquals($this->iso0110Instance->getField003(), "003000");
		$this->assertEquals($this->iso0110Instance->getField004(), "324324324234");
		$this->assertEquals($this->iso0110Instance->getField007(), "1107123000");
	}
}
