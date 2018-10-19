<?php

require_once(__DIR__.'/../messages/Message0210.php');

class Message0210Test extends PHPUnit_Framework_TestCase{
	private $original0210Msg;
	private $generated0210Msg;
	private $iso0210Instance;

	public function testMsg()
	{
		$this->original0210 = "0210B238000006C0000400000000000000020030000000000000101107123000270003123000071138383800006677880000000300000030160000000000123456006787878";

		$this->iso0210Instance = new Message0210();

		$this->assertTrue($this->iso0210Instance->setField003("003000"));
		$this->assertTrue($this->iso0210Instance->setField004("000000000010"));
		$this->assertTrue($this->iso0210Instance->setField007("1107123000"));
		$this->assertTrue($this->iso0210Instance->setField011("270003"));
		$this->assertTrue($this->iso0210Instance->setField012("123000"));
		$this->assertTrue($this->iso0210Instance->setField013("0711"));
		$this->assertTrue($this->iso0210Instance->setField038("383838"));
		$this->assertTrue($this->iso0210Instance->setField039("00"));
		$this->assertTrue($this->iso0210Instance->setField041("00667788"));
		$this->assertTrue($this->iso0210Instance->setField042("000000030000003"));
		$this->assertTrue($this->iso0210Instance->setField062("0000000000123456"));
		$this->assertTrue($this->iso0210Instance->setField127("787878"));

		$this->generated0210Msg = $this->iso0210Instance->getMessage();

		$this->assertTrue($this->generated0210Msg !== false);

		$this->assertEquals($this->original0210, $this->generated0210Msg);

		$this->iso0210Instance = null;

		$this->iso0210Instance = new Message0210($this->generated0210Msg);
		$this->assertTrue($this->iso0210Instance->success());

		$this->assertEquals($this->iso0210Instance->getField003(), "003000");
		$this->assertEquals($this->iso0210Instance->getField004(), "000000000010");
		$this->assertEquals($this->iso0210Instance->getField007(), "1107123000");
	}
}
