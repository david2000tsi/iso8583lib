<?php

require_once('../ISO8583.php');
require_once('Message.php');

class Message0400 extends Message
{
	const MTI = "0400"; // MTI of ISO8583:1987: 0400 message.

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

	public function setField002(string $value) { $this->isoInstance->addField(  2, $value); }
	public function setField003(string $value) { $this->isoInstance->addField(  3, $value); }
	public function setField004(string $value) { $this->isoInstance->addField(  4, $value); }
	public function setField007(string $value) { $this->isoInstance->addField(  7, $value); }
	public function setField011(string $value) { $this->isoInstance->addField( 11, $value); }
	public function setField012(string $value) { $this->isoInstance->addField( 12, $value); }
	public function setField013(string $value) { $this->isoInstance->addField( 13, $value); }
	public function setField022(string $value) { $this->isoInstance->addField( 22, $value); }
	public function setField041(string $value) { $this->isoInstance->addField( 41, $value); }
	public function setField042(string $value) { $this->isoInstance->addField( 42, $value); }
	public function setField048(string $value) { $this->isoInstance->addField( 48, $value); }
	public function setField052(string $value) { $this->isoInstance->addField( 52, $value); }
	public function setField061(string $value) { $this->isoInstance->addField( 61, $value); }
	public function setField063(string $value) { $this->isoInstance->addField( 63, $value); }
	public function setField090(string $value) { $this->isoInstance->addField( 90, $value); }
	public function setField125(string $value) { $this->isoInstance->addField(125, $value); }

	public function getField002() { return $this->isoInstance->getField(  2); }
	public function getField003() { return $this->isoInstance->getField(  3); }
	public function getField004() { return $this->isoInstance->getField(  4); }
	public function getField007() { return $this->isoInstance->getField(  7); }
	public function getField011() { return $this->isoInstance->getField( 11); }
	public function getField012() { return $this->isoInstance->getField( 12); }
	public function getField013() { return $this->isoInstance->getField( 13); }
	public function getField022() { return $this->isoInstance->getField( 22); }
	public function getField041() { return $this->isoInstance->getField( 41); }
	public function getField042() { return $this->isoInstance->getField( 42); }
	public function getField048() { return $this->isoInstance->getField( 48); }
	public function getField052() { return $this->isoInstance->getField( 52); }
	public function getField061() { return $this->isoInstance->getField( 61); }
	public function getField063() { return $this->isoInstance->getField( 63); }
	public function getField090() { return $this->isoInstance->getField( 90); }
	public function getField125() { return $this->isoInstance->getField(125); }

	public function getMessage()
	{
		if($this->success)
		{
			$this->success &= !empty($this->getField002());
			$this->success &= !empty($this->getField003());
			$this->success &= !empty($this->getField004());
			$this->success &= !empty($this->getField007());
			$this->success &= !empty($this->getField011());
			$this->success &= !empty($this->getField012());
			$this->success &= !empty($this->getField013());
			$this->success &= !empty($this->getField022());
			$this->success &= !empty($this->getField041());
			$this->success &= !empty($this->getField042());
			$this->success &= !empty($this->getField048());
			$this->success &= !empty($this->getField052());
			$this->success &= !empty($this->getField061());
			$this->success &= !empty($this->getField063());
			$this->success &= !empty($this->getField090());
			$this->success &= !empty($this->getField125());
		}

		if($this->success)
		{
			return $this->isoInstance->generateMessage();
		}

		return false;
	}
}
