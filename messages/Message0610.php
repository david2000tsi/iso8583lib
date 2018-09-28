<?php

require_once('../ISO8583.php');

class Message0610 {
	const MTI = "0610"; // MTI of ISO8583:1987: 0610 message.

	private $isoMsg;

	private $field_003;
	private $field_004;
	private $field_007;
	private $field_011;
	private $field_012;
	private $field_013;
	private $field_039;
	private $field_041;
	private $field_042;
	private $field_127;

	public function __construct()
	{
		$this->isoMsg = new ISO8583(ISO8583::ISO8583_1987);

		$this->field_003 = "";
		$this->field_004 = "";
		$this->field_007 = "";
		$this->field_011 = "";
		$this->field_012 = "";
		$this->field_013 = "";
		$this->field_039 = "";
		$this->field_041 = "";
		$this->field_042 = "";
		$this->field_127 = "";
	}

	public function setField003($value)	{ $this->field_003 = $value; }
	public function setField004($value)	{ $this->field_004 = $value; }
	public function setField007($value)	{ $this->field_007 = $value; }
	public function setField011($value)	{ $this->field_011 = $value; }
	public function setField012($value)	{ $this->field_012 = $value; }
	public function setField013($value)	{ $this->field_013 = $value; }
	public function setField039($value)	{ $this->field_039 = $value; }
	public function setField041($value)	{ $this->field_041 = $value; }
	public function setField042($value)	{ $this->field_042 = $value; }
	public function setField127($value)	{ $this->field_127 = $value; }

	public function getField003($value)	{ return $this->field_003; }
	public function getField004($value)	{ return $this->field_004; }
	public function getField007($value)	{ return $this->field_007; }
	public function getField011($value)	{ return $this->field_011; }
	public function getField012($value)	{ return $this->field_012; }
	public function getField013($value)	{ return $this->field_013; }
	public function getField039($value)	{ return $this->field_039; }
	public function getField041($value)	{ return $this->field_041; }
	public function getField042($value)	{ return $this->field_042; }
	public function getField127($value)	{ return $this->field_127; }

	public function getMessage()
	{
		$ret = $this->isoMsg->success();
		if($ret)
		{
			$ret &= $this->isoMsg->setMti(self::MTI);
			$ret &= strlen($this->field_003) ? $this->isoMsg->addField(  3, $this->field_003) : false;
			$ret &= strlen($this->field_004) ? $this->isoMsg->addField(  4, $this->field_004) : false;
			$ret &= strlen($this->field_007) ? $this->isoMsg->addField(  7, $this->field_007) : false;
			$ret &= strlen($this->field_011) ? $this->isoMsg->addField( 11, $this->field_011) : false;
			$ret &= strlen($this->field_012) ? $this->isoMsg->addField( 12, $this->field_012) : false;
			$ret &= strlen($this->field_013) ? $this->isoMsg->addField( 13, $this->field_013) : false;
			$ret &= strlen($this->field_039) ? $this->isoMsg->addField( 39, $this->field_039) : false;
			$ret &= strlen($this->field_041) ? $this->isoMsg->addField( 41, $this->field_041) : false;
			$ret &= strlen($this->field_042) ? $this->isoMsg->addField( 42, $this->field_042) : false;
			$ret &= strlen($this->field_127) ? $this->isoMsg->addField(127, $this->field_127) : false;
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

		$this->field_003 = $this->isoMsg->getField(  3);
		$this->field_004 = $this->isoMsg->getField(  4);
		$this->field_007 = $this->isoMsg->getField(  7);
		$this->field_011 = $this->isoMsg->getField( 11);
		$this->field_012 = $this->isoMsg->getField( 12);
		$this->field_013 = $this->isoMsg->getField( 13);
		$this->field_039 = $this->isoMsg->getField( 39);
		$this->field_041 = $this->isoMsg->getField( 41);
		$this->field_042 = $this->isoMsg->getField( 42);
		$this->field_127 = $this->isoMsg->getField(127);
	}
}
