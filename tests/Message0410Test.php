<?php

require_once('../messages/Message0410.php');

class Message0410Test extends PHPUnit_Framework_TestCase{
	private $original0410Msg;
	private $generated0410Msg;
	private $iso0410Instance;

	public function testMsg()
	{
		$this->original0410 = "0410B238000006C0000400000040000000020030000000000000101107123000270003123000071155555544006677880000000300000030160000000000123456000000000000000000000000009999999999999999009012345678";

		$this->iso0410Instance = new Message0410();

		$this->iso0410Instance->setField003("003000");
		$this->iso0410Instance->setField004("000000000010");
		$this->iso0410Instance->setField007("1107123000");
		$this->iso0410Instance->setField011("270003");
		$this->iso0410Instance->setField012("123000");
		$this->iso0410Instance->setField013("0711");
		$this->iso0410Instance->setField038("555555");
		$this->iso0410Instance->setField039("44");
		$this->iso0410Instance->setField041("00667788");
		$this->iso0410Instance->setField042("000000030000003");
		$this->iso0410Instance->setField062("0000000000123456");
		$this->iso0410Instance->setField090("000000000000000000000000009999999999999999");
		$this->iso0410Instance->setField127("012345678");

		$this->generated0410Msg = $this->iso0410Instance->getMessage();

		$this->assertTrue($this->generated0410Msg !== false);

		$this->assertEquals($this->original0410, $this->generated0410Msg);

		$this->iso0410Instance = null;

		$this->iso0410Instance = new Message0410($this->generated0410Msg);
		$this->assertTrue($this->iso0410Instance->success());

		$this->assertEquals($this->iso0410Instance->getField003(), "003000");
		$this->assertEquals($this->iso0410Instance->getField004(), "000000000010");
		$this->assertEquals($this->iso0410Instance->getField007(), "1107123000");
	}
}
