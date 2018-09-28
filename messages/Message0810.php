<?php

require_once('../ISO8583.php');

class Message0810 {
	const MTI = "0810"; // MTI of ISO8583:1987: 0810 message.

	private $isoMsg;

	private $field_007;
	private $field_011;
	private $field_012;
	private $field_013;
	private $field_039;
	private $field_041;
	private $field_042;
	private $field_053;
	private $field_070;

	public function __construct()
	{
		$this->isoMsg = new ISO8583(ISO8583::ISO8583_1987);

		$this->field_007 = "";
		$this->field_011 = "";
		$this->field_012 = "";
		$this->field_013 = "";
		$this->field_039 = "";
		$this->field_041 = "";
		$this->field_042 = "";
		$this->field_053 = "";
		$this->field_070 = "";
	}

	public function setField007(string $value) { $this->field_007 = $value; }
	public function setField011(string $value) { $this->field_011 = $value; }
	public function setField012(string $value) { $this->field_012 = $value; }
	public function setField013(string $value) { $this->field_013 = $value; }
	public function setField039(string $value) { $this->field_039 = $value; }
	public function setField041(string $value) { $this->field_041 = $value; }
	public function setField042(string $value) { $this->field_042 = $value; }
	public function setField053(string $value) { $this->field_053 = $value; }
	public function setField070(string $value) { $this->field_070 = $value; }

	public function getField007() { return $this->field_007; }
	public function getField011() { return $this->field_011; }
	public function getField012() { return $this->field_012; }
	public function getField013() { return $this->field_013; }
	public function getField039() { return $this->field_039; }
	public function getField041() { return $this->field_041; }
	public function getField042() { return $this->field_042; }
	public function getField053() { return $this->field_053; }
	public function getField070() { return $this->field_070; }

	public function getMessage()
	{
		$ret = $this->isoMsg->success();
		if($ret)
		{
			$ret &= $this->isoMsg->setMti(self::MTI);
			$ret &= strlen($this->field_007) ? $this->isoMsg->addField( 7, $this->field_007) : false;
			$ret &= strlen($this->field_011) ? $this->isoMsg->addField(11, $this->field_011) : false;
			$ret &= strlen($this->field_012) ? $this->isoMsg->addField(12, $this->field_012) : false;
			$ret &= strlen($this->field_013) ? $this->isoMsg->addField(13, $this->field_013) : false;
			$ret &= strlen($this->field_039) ? $this->isoMsg->addField(39, $this->field_039) : false;
			$ret &= strlen($this->field_041) ? $this->isoMsg->addField(41, $this->field_041) : false;
			$ret &= strlen($this->field_042) ? $this->isoMsg->addField(42, $this->field_042) : false;
			$ret &= strlen($this->field_053) ? $this->isoMsg->addField(53, $this->field_053) : false;
			$ret &= strlen($this->field_070) ? $this->isoMsg->addField(70, $this->field_070) : false;
		}

		if($ret)
		{
			return $this->isoMsg->generateMessage();
		}

		return false;
	}

	public function decodeMessage(string $message)
	{
		$this->isoMsg->decodeMessage($message, ISO8583::ISO8583_1987);

		$this->field_007 = $this->isoMsg->getField( 7);
		$this->field_011 = $this->isoMsg->getField(11);
		$this->field_012 = $this->isoMsg->getField(12);
		$this->field_013 = $this->isoMsg->getField(13);
		$this->field_039 = $this->isoMsg->getField(39);
		$this->field_041 = $this->isoMsg->getField(41);
		$this->field_042 = $this->isoMsg->getField(42);
		$this->field_053 = $this->isoMsg->getField(53);
		$this->field_070 = $this->isoMsg->getField(70);
	}
}
