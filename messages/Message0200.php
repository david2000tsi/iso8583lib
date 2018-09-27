<?php

require_once('../ISO8583.php');

class Message0200 {
	const MTI = "0200"; // MTI of ISO8583:1987: 0200 message.

	private $isoMsg;

	private $field_002;
	private $field_003;
	private $field_004;
	private $field_007;
	private $field_011;
	private $field_012;
	private $field_013;
	private $field_014;
	private $field_022;
	private $field_041;
	private $field_042;
	private $field_048;
	private $field_052;
	private $field_061;
	private $field_062;
	private $field_063;

	public function __construct()
	{
		$this->isoMsg = new ISO8583(ISO8583::ISO8583_1987);

		$this->field_002 = "";
		$this->field_003 = "";
		$this->field_004 = "";
		$this->field_007 = "";
		$this->field_011 = "";
		$this->field_012 = "";
		$this->field_013 = "";
		$this->field_014 = "";
		$this->field_022 = "";
		$this->field_041 = "";
		$this->field_042 = "";
		$this->field_048 = "";
		$this->field_052 = "";
		$this->field_061 = "";
		$this->field_062 = "";
		$this->field_063 = "";
	}

	public function setField002($value)	{ $this->field_002 = $value; }
	public function setField003($value)	{ $this->field_003 = $value; }
	public function setField004($value)	{ $this->field_004 = $value; }
	public function setField007($value)	{ $this->field_007 = $value; }
	public function setField011($value)	{ $this->field_011 = $value; }
	public function setField012($value)	{ $this->field_012 = $value; }
	public function setField013($value)	{ $this->field_013 = $value; }
	public function setField014($value)	{ $this->field_014 = $value; }
	public function setField022($value)	{ $this->field_022 = $value; }
	public function setField041($value)	{ $this->field_041 = $value; }
	public function setField042($value)	{ $this->field_042 = $value; }
	public function setField048($value)	{ $this->field_048 = $value; }
	public function setField052($value)	{ $this->field_052 = $value; }
	public function setField061($value)	{ $this->field_061 = $value; }
	public function setField062($value)	{ $this->field_062 = $value; }
	public function setField063($value)	{ $this->field_063 = $value; }

	public function getField002($value)	{ return $this->field_002; }
	public function getField003($value)	{ return $this->field_003; }
	public function getField004($value)	{ return $this->field_004; }
	public function getField007($value)	{ return $this->field_007; }
	public function getField011($value)	{ return $this->field_011; }
	public function getField012($value)	{ return $this->field_012; }
	public function getField013($value)	{ return $this->field_013; }
	public function getField014($value)	{ return $this->field_014; }
	public function getField022($value)	{ return $this->field_022; }
	public function getField041($value)	{ return $this->field_041; }
	public function getField042($value)	{ return $this->field_042; }
	public function getField048($value)	{ return $this->field_048; }
	public function getField052($value)	{ return $this->field_052; }
	public function getField061($value)	{ return $this->field_061; }
	public function getField062($value)	{ return $this->field_062; }
	public function getField063($value)	{ return $this->field_063; }

	public function getMessage()
	{
		$ret = $this->isoMsg->success();
		if($ret)
		{
			$ret &= $this->isoMsg->setMti(self::MTI);
			$ret &= strlen($this->field_002) ? $this->isoMsg->addField( 2, $this->field_002) : false;
			$ret &= strlen($this->field_003) ? $this->isoMsg->addField( 3, $this->field_003) : false;
			$ret &= strlen($this->field_004) ? $this->isoMsg->addField( 4, $this->field_004) : false;
			$ret &= strlen($this->field_007) ? $this->isoMsg->addField( 7, $this->field_007) : false;
			$ret &= strlen($this->field_011) ? $this->isoMsg->addField(11, $this->field_011) : false;
			$ret &= strlen($this->field_012) ? $this->isoMsg->addField(12, $this->field_012) : false;
			$ret &= strlen($this->field_013) ? $this->isoMsg->addField(13, $this->field_013) : false;
			$ret &= strlen($this->field_014) ? $this->isoMsg->addField(14, $this->field_014) : false;
			$ret &= strlen($this->field_022) ? $this->isoMsg->addField(22, $this->field_022) : false;
			$ret &= strlen($this->field_041) ? $this->isoMsg->addField(41, $this->field_041) : false;
			$ret &= strlen($this->field_042) ? $this->isoMsg->addField(42, $this->field_042) : false;
			$ret &= strlen($this->field_048) ? $this->isoMsg->addField(48, $this->field_048) : false;
			$ret &= strlen($this->field_052) ? $this->isoMsg->addField(52, $this->field_052) : false;
			$ret &= strlen($this->field_061) ? $this->isoMsg->addField(61, $this->field_061) : false;
			$ret &= strlen($this->field_062) ? $this->isoMsg->addField(62, $this->field_062) : false;
			$ret &= strlen($this->field_063) ? $this->isoMsg->addField(63, $this->field_063) : false;
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

		$this->field_002 = $this->isoMsg->getField( 2);
		$this->field_003 = $this->isoMsg->getField( 3);
		$this->field_004 = $this->isoMsg->getField( 4);
		$this->field_007 = $this->isoMsg->getField( 7);
		$this->field_011 = $this->isoMsg->getField(11);
		$this->field_012 = $this->isoMsg->getField(12);
		$this->field_013 = $this->isoMsg->getField(13);
		$this->field_014 = $this->isoMsg->getField(14);
		$this->field_022 = $this->isoMsg->getField(22);
		$this->field_041 = $this->isoMsg->getField(41);
		$this->field_042 = $this->isoMsg->getField(42);
		$this->field_048 = $this->isoMsg->getField(48);
		$this->field_052 = $this->isoMsg->getField(52);
		$this->field_061 = $this->isoMsg->getField(61);
		$this->field_062 = $this->isoMsg->getField(62);
		$this->field_063 = $this->isoMsg->getField(63);
	}
}
