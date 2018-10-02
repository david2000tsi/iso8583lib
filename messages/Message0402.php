<?php

require_once(__DIR__.'/Message.php');
require_once(__DIR__.'/../ISO8583.php');

class Message0402 extends Message
{
	const MTI = "0402"; // MTI of ISO8583:1987: 0402 message.

	public function __construct(string $isoMsg = "")
	{
		$this->isoInstance = new ISO8583(ISO8583::ISO8583_1987, $isoMsg);
		$this->success = $this->isoInstance->success();

		// Case $isoMsg is empty the user wants to create iso msg, soh lets go to intialize the instance.
		// Case $isoMsg is not empty the user wants to decode a passed message.
		if(empty($isoMsg) && $this->success)
		{
			$this->isoInstance->setMti(self::MTI);
		}
	}

	public function setField003(string $value) { $this->isoInstance->addField(  3, $value); }
	public function setField004(string $value) { $this->isoInstance->addField(  4, $value); }
	public function setField007(string $value) { $this->isoInstance->addField(  7, $value); }
	public function setField011(string $value) { $this->isoInstance->addField( 11, $value); }
	public function setField012(string $value) { $this->isoInstance->addField( 12, $value); }
	public function setField013(string $value) { $this->isoInstance->addField( 13, $value); }
	public function setField039(string $value) { $this->isoInstance->addField( 39, $value); }
	public function setField041(string $value) { $this->isoInstance->addField( 41, $value); }
	public function setField042(string $value) { $this->isoInstance->addField( 42, $value); }
	public function setField127(string $value) { $this->isoInstance->addField(127, $value); }

	public function getField003() { return $this->isoInstance->getField(  3); }
	public function getField004() { return $this->isoInstance->getField(  4); }
	public function getField007() { return $this->isoInstance->getField(  7); }
	public function getField011() { return $this->isoInstance->getField( 11); }
	public function getField012() { return $this->isoInstance->getField( 12); }
	public function getField013() { return $this->isoInstance->getField( 13); }
	public function getField039() { return $this->isoInstance->getField( 39); }
	public function getField041() { return $this->isoInstance->getField( 41); }
	public function getField042() { return $this->isoInstance->getField( 42); }
	public function getField127() { return $this->isoInstance->getField(127); }

	public function getMessage()
	{
		if($this->success)
		{
			$this->success &= !empty($this->getField003());
			$this->success &= !empty($this->getField004());
			$this->success &= !empty($this->getField007());
			$this->success &= !empty($this->getField011());
			$this->success &= !empty($this->getField012());
			$this->success &= !empty($this->getField013());
			$this->success &= !empty($this->getField039());
			$this->success &= !empty($this->getField041());
			$this->success &= !empty($this->getField042());
			$this->success &= !empty($this->getField127());
		}

		if($this->success)
		{
			return $this->isoInstance->generateMessage();
		}

		return false;
	}
}
