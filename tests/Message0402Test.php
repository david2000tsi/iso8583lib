<?php

require_once(__DIR__.'/../vendor/autoload.php');

class Message0402Test extends PHPUnit_Framework_TestCase{
	private $original0402Msg;
	private $generated0402Msg;
	private $iso0402Instance;

	public function testMsg()
	{
		$this->original0402 = "0402B238000002C000000000000000000002003000000000000010110712300027000312300007114400667788000000030000003009012345678";

		$this->iso0402Instance = new Message0402();

		$this->assertTrue($this->iso0402Instance->setField003("003000"));
		$this->assertTrue($this->iso0402Instance->setField004("000000000010"));
		$this->assertTrue($this->iso0402Instance->setField007("1107123000"));
		$this->assertTrue($this->iso0402Instance->setField011("270003"));
		$this->assertTrue($this->iso0402Instance->setField012("123000"));
		$this->assertTrue($this->iso0402Instance->setField013("0711"));
		$this->assertTrue($this->iso0402Instance->setField039("44"));
		$this->assertTrue($this->iso0402Instance->setField041("00667788"));
		$this->assertTrue($this->iso0402Instance->setField042("000000030000003"));
		$this->assertTrue($this->iso0402Instance->setField127("012345678"));

		$this->generated0402Msg = $this->iso0402Instance->getMessage();

		$this->assertTrue($this->generated0402Msg !== false);

		$this->assertEquals($this->original0402, $this->generated0402Msg);

		$this->iso0402Instance = null;

		$this->iso0402Instance = new Message0402($this->generated0402Msg);
		$this->assertTrue($this->iso0402Instance->success());

		$this->assertEquals($this->iso0402Instance->getField003(), "003000");
		$this->assertEquals($this->iso0402Instance->getField004(), "000000000010");
		$this->assertEquals($this->iso0402Instance->getField007(), "1107123000");
	}
}
