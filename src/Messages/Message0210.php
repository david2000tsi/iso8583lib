<?php

namespace ISO8583LIB\Messages;

require_once(__DIR__.'/../../vendor/autoload.php');

use ISO8583LIB\ISO8583;

class Message0210 extends Message
{
	const MTI = "0210"; // MTI of ISO8583:1987: 0210 message.

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

	public function setField003(string $value) { return $this->isoInstance->addField(  3, $value); }
	public function setField004(string $value) { return $this->isoInstance->addField(  4, $value); }
	public function setField007(string $value) { return $this->isoInstance->addField(  7, $value); }
	public function setField011(string $value) { return $this->isoInstance->addField( 11, $value); }
	public function setField012(string $value) { return $this->isoInstance->addField( 12, $value); }
	public function setField013(string $value) { return $this->isoInstance->addField( 13, $value); }
	public function setField038(string $value) { return $this->isoInstance->addField( 38, $value); }
	public function setField039(string $value) { return $this->isoInstance->addField( 39, $value); }
	public function setField041(string $value) { return $this->isoInstance->addField( 41, $value); }
	public function setField042(string $value) { return $this->isoInstance->addField( 42, $value); }
	public function setField062(string $value) { return $this->isoInstance->addField( 62, $value); }
	public function setField127(string $value) { return $this->isoInstance->addField(127, $value); }

	public function getField003() { return $this->isoInstance->getField(  3); }
	public function getField004() { return $this->isoInstance->getField(  4); }
	public function getField007() { return $this->isoInstance->getField(  7); }
	public function getField011() { return $this->isoInstance->getField( 11); }
	public function getField012() { return $this->isoInstance->getField( 12); }
	public function getField013() { return $this->isoInstance->getField( 13); }
	public function getField038() { return $this->isoInstance->getField( 38); }
	public function getField039() { return $this->isoInstance->getField( 39); }
	public function getField041() { return $this->isoInstance->getField( 41); }
	public function getField042() { return $this->isoInstance->getField( 42); }
	public function getField062() { return $this->isoInstance->getField( 62); }
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
			$this->success &= !empty($this->getField038());
			$this->success &= !empty($this->getField039());
			$this->success &= !empty($this->getField041());
			$this->success &= !empty($this->getField042());
			$this->success &= !empty($this->getField062());
			$this->success &= !empty($this->getField127());
		}

		if($this->success)
		{
			return $this->isoInstance->generateMessage();
		}

		return false;
	}
}
