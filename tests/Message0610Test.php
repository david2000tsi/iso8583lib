<?php

require_once('../messages/Message0610.php');

class Message0610Test extends PHPUnit_Framework_TestCase{
	private $original0610Msg;
	private $generated0610Msg;
	private $iso0610Instance;

	public function testMsg()
	{
		$this->original0610 = "0610B238000002C000000000000000000002003000453632453465110712300045634612300021503941414141243654364564564009012345678";

		$this->iso0610Instance = new Message0610();

		$this->iso0610Instance->setField003("003000");
		$this->iso0610Instance->setField004("453632453465");
		$this->iso0610Instance->setField007("1107123000");
		$this->iso0610Instance->setField011("456346");
		$this->iso0610Instance->setField012("123000");
		$this->iso0610Instance->setField013("2150");
		$this->iso0610Instance->setField039("39");
		$this->iso0610Instance->setField041("41414141");
		$this->iso0610Instance->setField042("243654364564564");
		$this->iso0610Instance->setField127("012345678");

		$this->generated0610Msg = $this->iso0610Instance->getMessage();

		$this->assertTrue($this->generated0610Msg !== false);

		$this->assertEquals($this->original0610, $this->generated0610Msg);

		$this->iso0610Instance = null;

		$this->iso0610Instance = new Message0610($this->generated0610Msg);
		$this->assertTrue($this->iso0610Instance->success());

		$this->assertEquals($this->iso0610Instance->getField003(), "003000");
		$this->assertEquals($this->iso0610Instance->getField004(), "453632453465");
		$this->assertEquals($this->iso0610Instance->getField007(), "1107123000");
	}
}
