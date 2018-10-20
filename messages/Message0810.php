<?php

require_once(__DIR__.'/Message.php');
require_once(__DIR__.'/../ISO8583.php');

class Message0810 extends Message
{
	const MTI = "0810"; // MTI of ISO8583:1987: 0810 message.

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

	public function setField007(string $value) { return $this->isoInstance->addField( 7, $value); }
	public function setField011(string $value) { return $this->isoInstance->addField(11, $value); }
	public function setField012(string $value) { return $this->isoInstance->addField(12, $value); }
	public function setField013(string $value) { return $this->isoInstance->addField(13, $value); }
	public function setField039(string $value) { return $this->isoInstance->addField(39, $value); }
	public function setField041(string $value) { return $this->isoInstance->addField(41, $value); }
	public function setField042(string $value) { return $this->isoInstance->addField(42, $value); }
	public function setField053(string $value) { return $this->isoInstance->addField(53, $value); }
	public function setField070(string $value) { return $this->isoInstance->addField(70, $value); }

	public function getField007() { return $this->isoInstance->getField( 7); }
	public function getField011() { return $this->isoInstance->getField(11); }
	public function getField012() { return $this->isoInstance->getField(12); }
	public function getField013() { return $this->isoInstance->getField(13); }
	public function getField039() { return $this->isoInstance->getField(39); }
	public function getField041() { return $this->isoInstance->getField(41); }
	public function getField042() { return $this->isoInstance->getField(42); }
	public function getField053() { return $this->isoInstance->getField(53); }
	public function getField070() { return $this->isoInstance->getField(70); }

	public function getMessage()
	{
		if($this->success)
		{
			$this->success &= !empty($this->getField007());
			$this->success &= !empty($this->getField011());
			$this->success &= !empty($this->getField012());
			$this->success &= !empty($this->getField013());
			$this->success &= !empty($this->getField039());
			$this->success &= !empty($this->getField041());
			$this->success &= !empty($this->getField042());
			$this->success &= !empty($this->getField053());
			$this->success &= !empty($this->getField070());
		}

		if($this->success)
		{
			return $this->isoInstance->generateMessage();
		}

		return false;
	}
}
