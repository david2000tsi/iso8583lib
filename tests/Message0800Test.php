<?php

require_once(__DIR__.'/../messages/Message0800.php');

class Message0800Test extends PHPUnit_Framework_TestCase{
	private $original0800Msg;
	private $generated0800Msg;
	private $iso0800Instance;

	public function testMsg()
	{
		$this->original0800 = "08008238000000C0000004000000000000000717123000999999123000071700667788000000030000003001";

		$this->iso0800Instance = new Message0800();

		$this->iso0800Instance->setField007("0717123000");
		$this->iso0800Instance->setField011("999999");
		$this->iso0800Instance->setField012("123000");
		$this->iso0800Instance->setField013("0717");
		$this->iso0800Instance->setField041("00667788");
		$this->iso0800Instance->setField042("000000030000003");
		$this->iso0800Instance->setField070("001");

		$this->generated0800Msg = $this->iso0800Instance->getMessage();

		$this->assertTrue($this->generated0800Msg !== false);

		$this->assertEquals($this->original0800, $this->generated0800Msg);

		$this->iso0800Instance = null;

		$this->iso0800Instance = new Message0800($this->generated0800Msg);
		$this->assertTrue($this->iso0800Instance->success());

		$this->assertEquals($this->iso0800Instance->getField007(), "0717123000");
		$this->assertEquals($this->iso0800Instance->getField011(), "999999");
		$this->assertEquals($this->iso0800Instance->getField012(), "123000");
	}
}
