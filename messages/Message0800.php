<?php

require_once('../ISO8583.php');

class Message0800 {
	const MTI = "0800"; // MTI of ISO8583:1987: 0800 message.

	private $isoMsg;

	private $field_007;
	private $field_011;
	private $field_012;
	private $field_013;
	private $field_041;
	private $field_042;
	private $field_070;

	public function __construct()
	{
		$this->isoMsg = new ISO8583(ISO8583::ISO8583_1987);

		$this->field_007 = "";
		$this->field_011 = "";
		$this->field_012 = "";
		$this->field_013 = "";
		$this->field_041 = "";
		$this->field_042 = "";
		$this->field_070 = "";
	}

	public function setField007($value)	{ $this->field_007 = $value; }
	public function setField011($value)	{ $this->field_011 = $value; }
	public function setField012($value)	{ $this->field_012 = $value; }
	public function setField013($value)	{ $this->field_013 = $value; }
	public function setField041($value)	{ $this->field_041 = $value; }
	public function setField042($value)	{ $this->field_042 = $value; }
	public function setField070($value)	{ $this->field_070 = $value; }

	public function getField007($value)	{ return $this->field_007; }
	public function getField011($value)	{ return $this->field_011; }
	public function getField012($value)	{ return $this->field_012; }
	public function getField013($value)	{ return $this->field_013; }
	public function getField041($value)	{ return $this->field_041; }
	public function getField042($value)	{ return $this->field_042; }
	public function getField070($value)	{ return $this->field_070; }

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
			$ret &= strlen($this->field_041) ? $this->isoMsg->addField(41, $this->field_041) : false;
			$ret &= strlen($this->field_042) ? $this->isoMsg->addField(42, $this->field_042) : false;
			$ret &= strlen($this->field_070) ? $this->isoMsg->addField(70, $this->field_070) : false;
		}

		if($ret)
		{
			return $this->isoMsg->generateMessage();
		}

		return false;
	}

	public function decodeMessage($message)
	{
		$this->isoMsg->decodeMessage($message, ISO8583::ISO8583_1987);

		$this->field_007 = $this->isoMsg->getField( 7);
		$this->field_011 = $this->isoMsg->getField(11);
		$this->field_012 = $this->isoMsg->getField(12);
		$this->field_013 = $this->isoMsg->getField(13);
		$this->field_041 = $this->isoMsg->getField(41);
		$this->field_042 = $this->isoMsg->getField(42);
		$this->field_070 = $this->isoMsg->getField(70);
	}
}
