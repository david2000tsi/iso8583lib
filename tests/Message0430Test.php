<?php

require_once(__DIR__.'/../messages/Message0430.php');

class Message0430Test extends PHPUnit_Framework_TestCase{
	private $original0430Msg;
	private $generated0430Msg;
	private $iso0430Instance;

	public function testMsg()
	{
		$this->original0430 = "0430B238000002C000000000000000000002003000000000000010110712300027000312300007114400667788000000030000003009012345678";

		$this->iso0430Instance = new Message0430();

		$this->iso0430Instance->setField003("003000");
		$this->iso0430Instance->setField004("000000000010");
		$this->iso0430Instance->setField007("1107123000");
		$this->iso0430Instance->setField011("270003");
		$this->iso0430Instance->setField012("123000");
		$this->iso0430Instance->setField013("0711");
		$this->iso0430Instance->setField039("44");
		$this->iso0430Instance->setField041("00667788");
		$this->iso0430Instance->setField042("000000030000003");
		$this->iso0430Instance->setField127("012345678");

		$this->generated0430Msg = $this->iso0430Instance->getMessage();

		$this->assertTrue($this->generated0430Msg !== false);

		$this->assertEquals($this->original0430, $this->generated0430Msg);

		$this->iso0430Instance = null;

		$this->iso0430Instance = new Message0430($this->generated0430Msg);
		$this->assertTrue($this->iso0430Instance->success());

		$this->assertEquals($this->iso0430Instance->getField003(), "003000");
		$this->assertEquals($this->iso0430Instance->getField004(), "000000000010");
		$this->assertEquals($this->iso0430Instance->getField007(), "1107123000");
	}
}
