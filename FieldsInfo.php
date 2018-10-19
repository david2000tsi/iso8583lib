<?php

class FieldsInfo
{
	// Definitions and legend for abbreviations:
	const __A                  = "a";   // Alhpabetical characters (A-Z, a-z);
	const __N                  = "n";   // Numeric digits, (0-9);
	const __P                  = "p";   // Pad character (space);
	const __S                  = "s";   // Special characters;
	const __AN                 = "an";  // Alphabetical and numeric characters;
	const __AS                 = "as";  // Alphabetical and special characters;
	const __NS                 = "ns";  // Numeric and special characters;
	const __ANP                = "anp"; // Alphabetical, numeric and space (pad) characters;
	const __ANS                = "ans"; // Alphabetical, numeric and special characters;

	const __B                  = "b";   // Binary representation of data;
	const __Z                  = "z";   // Tracks 2 and 3 code set as defined in ISO4909 and ISO7813;

	const __X                  = "x";   // 'C' for Credit and 'D' for Debit and shall always be associated wih a numeric amount data element.
	//I.e., x+n16 in amount, net reconciliation means prefix 'C' or 'D' and 16 digits of amount, net reconciliation.
	const __XN                  = self::__X."+ ".self::__N;

	const __MM                 = "MM";  // Month  (01-12);
	const __DD                 = "DD";  // Day    (01-31);
	const __YY                 = "YY";  // Year   (00-99);
	const __HH                 = "hh";  // Hour   (00-23);
	const __MIN                = "mm";  // Minute (01-59);
	const __SS                 = "ss";  // Second (01-59);

	const __LL                 = "LL";  // Length of variable data element (01-99);
	const __LLL                = "LLL"; // Length of variable data element (001-999);
	const __VAR                = "VAR"; // Variable length data element;

	const __YYMM               = self::__YY.self::__MM;
	const __YYMMDD             = self::__YY.self::__MM.self::__DD;
	const __MMDD               = self::__MM.self::__DD;
	const __HHMINSS            = self::__HH.self::__MIN.self::__SS;
	const __MMDDYYHHMMSS       = self::__MM.self::__DD.self::__YY.self::__HH.self::__MIN.self::__SS;

	const __LLVAR              = self::__LL.self::__VAR;
	const __LLLVAR             = self::__LLL.self::__VAR;

	const VARIABLE_FIELD_FALSE = false; // Fixed length;
	const VARIABLE_FIELD_TRUE  = true;  // Variable length up to maximun $fieldsInfo[$fieldNum]['length'] characters.

	// NOTE:
	// All vaiable length fields shall in addition contain two or three positions at the beginning of the data element
	// to identity the number of positions following to the end of that data element.

	// All fixed length 'n' (self::__N) data elements are assumed to be right justified with leadinf zeroes.
	// All other fixed length data elements are left justified with trailing spaces.
	// In all 'b' data elements, blocks of 8 bits are assumed to be left justified with trailing zeros.
	// All data elements are counted from left to right.

	const MTI_LEN_BYTES        = 4;
	const BITMAP_LEN_BITS      = 64;
	const BITMAP_LEN_BYTES     = (self::BITMAP_LEN_BITS / 8);
	const NUM_FIELD_MIN        = 1;
	const NUM_FIELD_MAX        = 128;

	// ISO Versions:
	const ISO8583_1987         = "0";
	const ISO8583_1993         = "1";
	const ISO8583_2003         = "2";

	// MTI (Message Type Identifier) Structure:
	// First position:
	//                  0 -> ISO8583:1987;
	//                  1 -> ISO8583:1993;
	//                  2 -> ISO8583:2003;
	//                  3-7 -> Reserved for ISO use;
	//                  8 -> Reserved for national use;
	//                  9 -> Reserved for private use;
	// Second position:
	//                  0 -> Reserved for ISO use;
	//                  1 -> Authorization;
	//                  2 -> Financial;
	//                  3 -> File action;
	//                  4 -> Reserval/chargeback;
	//                  5 -> Reconciliation;
	//                  6 -> Administrative;
	//                  7 -> Fee collection;
	//                  8 -> Network management;
	//                  9 -> Reserved for ISO use;
	// Third position:
	//                  0 -> Request;
	//                  1 -> Request response;
	//                  2 -> Advice;
	//                  3 -> Advice response;
	//                  4 -> Notification;
	//                  5 -> Notification acknowledgement;
	//                  6 -> Instruction (ISO8583:2003 only);
	//                  7 -> Instruction acknowledgement (ISO8583:2003 only);
	//                  8-9 -> Reserved for ISO use;
	// Fourth position:
	//                  0 -> Acquirer;
	//                  1 -> Acquirer repeat;
	//                  2 -> Card issuer;
	//                  3 -> Card issuer repeat;
	//                  4 -> Other
	//                  5 -> Other repeat;
	//                  6-9 -> Reserved for ISO use.

	private $mtiInfo;
	private $fieldsInfo;

	public function __construct(string $isoVersion)
	{
		$this->fieldsInfo = [];

		if($isoVersion == self::ISO8583_1987)
		{
			$this->fieldsInfo = $this->getISO8583_1987();

			// Adjust!
			$this->fieldsInfo[1]["length"] = 16;
			$this->fieldsInfo[52]["length"] = 16;
		}
		else if($isoVersion == self::ISO8583_1993)
		{
			$this->fieldsInfo = $this->getISO8583_1993();

			// Adjust!
			$this->fieldsInfo[1]["length"] = 16;
		}
		else if($isoVersion == self::ISO8583_2003)
		{
			$this->fieldsInfo = $this->getISO8583_2003();
		}
	}

	// Check instance status after call constructor.
	// Return false case error os success if the instance was created successfully.
	public function success()
	{
		return (!empty($this->fieldsInfo));
	}

	// Creates an array with one field information.
	// Return an array with the info.
	private static function mountFieldInfo(string $type, string $isVariableField, int $length, string $description, string $format = "")
	{
		return array("type" => $type, "isVariableField" => $isVariableField, "length" => $length, "description" => $description, "format" => $format);
	}

	// Retrieve field information from fields array.
	// Return field info according informed field number or false if an error occurs.
	public function getFieldInfo(int $field)
	{
		$info = false;

		if($field >= self::NUM_FIELD_MIN && $field <= self::NUM_FIELD_MAX)
		{
			$info = $this->fieldsInfo[$field];
		}

		return $info;
	}

	// Check if mti is valid.
	// Returns false if not or true if is a valid mti.
	public function isValidMti(string $mti)
	{
		// Available positions, for more info check comments above.
		$availablePositions  = array(
			array("0", "1", "2"),                          // First position;
			array("1", "2", "3", "4", "5", "6", "7", "8"), // Second position;
			array("0", "1", "2", "3", "4", "5", "6", "7"), // Third position;
			array("0", "1", "2", "3", "4", "5")            // Fourth position.
		);

		if(strlen($mti) == self::MTI_LEN_BYTES)
		{
			foreach($availablePositions as $key => $value)
			{
				if(!in_array($mti[$key], $value))
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	// Check if field is valid.
	// Returns false if not or true if is a valid field number.
	public function isValidField(int $field)
	{
		$fieldInfo = $this->getFieldInfo($field);
		return ($fieldInfo != false);
	}

	// Check if field and his value is valid.
	// Returns false if not or true if is a valid field number and value.
	public function isValidFieldValue(int $field, string $value)
	{
		$fieldInfo = $this->getFieldInfo($field);
		$valueLen = strlen($value);

		if($fieldInfo && $valueLen > 0)
		{
			if($fieldInfo["isVariableField"] && $valueLen <= $fieldInfo["length"])
			{
				return true;
			}
			else if($valueLen == $fieldInfo["length"])
			{
				return true;
			}
		}

		return false;
	}

	// Check if field length is variable.
	// Returns false if not or true if is a variable field length.
	public function isVariableField(int $field)
	{
		$fieldInfo = $this->getFieldInfo($field);
		return ($fieldInfo["isVariableField"]);
	}

	// Retrieve the size of length of variable field.
	// For field lengh 999 the result will be 3 (because 999 has 3 numeric digits).
	// Returns false if informed field number is not a variable field or the size of field length if the informed field is valid.
	public function getSizeOfLengthVariableField(int $field)
	{
		$fieldInfo = $this->getFieldInfo($field);
		if($fieldInfo["isVariableField"])
		{
			return strlen($fieldInfo["length"]);
		}

		return false;
	}

	// Return fields info for ISO8583:1987.
	private function getISO8583_1987()
	{
		return [
			1   => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,  64, "secondary bitmap"),
			2   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   19, "primary account number", self::__LLVAR),
			3   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "processing code"),
			4   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, transaction"),
			5   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, reconciliation"),
			6   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, cardholder biling"),
			7   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "date and time, transmission", self::__MMDDYYHHMMSS),
			8   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "amount, cardholder biling fee"),

			9   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "conversion rate, settlement"),
			10  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "conversion rate, cardholder biling"),
			11  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "system trace audit number"),
			12  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "date and time, local transaction", self::__HHMINSS),
			13  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, local transaction", self::__MMDD),
			14  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, expiration"),
			15  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, settlement"),
			16  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, conversion"),

			17  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, capture"),
			18  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "merchant type"),
			19  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, acquiring institution"),
			20  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, primary account number"),
			21  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, forwarding institution"),
			22  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "point of service data code"),
			23  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "card sequence number"),
			24  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "function code"),

			25  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   2, "point of sale condition code"),
			26  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   2, "point of sale capture code"),
			27  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   1, "authorization identification response length"),
			28  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,   8, "amount, transaction fee"),
			29  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,   8, "amount, settlement fee"),
			30  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,   8, "amount, transaction processing fee"),
			31  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,   8, "amount, settlement processing fee"),
			32  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "acquirer institution identification code", self::__LLVAR),

			33  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "fowarding institution identification code", self::__LLVAR),
			34  => self::mountFieldInfo(self::__NS,  self::VARIABLE_FIELD_TRUE,   28, "primary account number, extended", self::__LLVAR),
			35  => self::mountFieldInfo(self::__Z,   self::VARIABLE_FIELD_TRUE,   37, "track 2 data", self::__LLVAR),
			36  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,  104, "track 3 data", self::__LLLVAR),
			37  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,  12, "retrieval reference number"),
			38  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   6, "authorization identificarion response"),
			39  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   2, "response code"),
			40  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "service restriction code"),

			41  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,   8, "card acceptor terminal idetification"),
			42  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,  15, "card acceptor identification code"),
			43  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,  40, "card acceptor name/location", self::__LLVAR),
			44  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,   25, "aditional response data", self::__LLVAR),
			45  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,   76, "track 1 data", self::__LLVAR),
			46  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,  999, "addicional data (iso)", self::__LLLVAR),
			47  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,  999, "additional data, national", self::__LLLVAR),
			48  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,  999, "additional data, private", self::__LLLVAR),

			49  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, transaction"),
			50  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, settlement"),
			51  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, cardholder biling"),
			52  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "personal identification number (PIN) data"),
			53  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "security related control information", self::__LLVAR),
			54  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,  120, "amounts, additional", self::__LLLVAR),
			55  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "integrated circuit card system related data", self::__LLLVAR),
			56  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved (iso)", self::__LLLVAR),

			57  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			58  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			59  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			60  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			61  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for private use", self::__LLLVAR),
			62  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for private use", self::__LLLVAR),
			63  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for private use", self::__LLLVAR),
			64  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,  16, "message authentication code (mac)"),

			65  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   1, "extended bitmap indicator"),
			66  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   1, "settlement code"),
			67  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   2, "extended payment code"),
			68  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, receiving institution"),
			69  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, settlement institution"),
			70  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "network management institution code"),
			71  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "message number"),
			72  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "last message number"),

			73  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "date, action", self::__YYMMDD),
			74  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "credits, number"),
			75  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "credits, reversal number"),
			76  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "debits, number"),
			77  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "debits, reversal number"),
			78  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "transfer number"),
			79  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "transfer, reversal number"),
			80  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "inquiries, number"),

			81  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "authorizations, number"),
			82  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "credits, processing fee amount"),
			83  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "credits, transaction fee amount"),
			84  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "debits, processing fee amount"),
			85  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "debits, transaction fee amount"),
			86  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "credits, total amount"),
			87  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "credits, reversal amount"),
			88  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "debits, total amount"),

			89  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "debits, reversal amount"),
			90  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  42, "original data elements"),
			91  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   1, "file update code"),
			92  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   2, "file securiry code"),
			93  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   5, "response indicator"),
			94  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   7, "service indicator"),
			95  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,  42, "replacement amounts"),
			96  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,  64, "message securiry code"),

			97  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,  16, "amount, net settlement"),
			98  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,  25, "payee"),
			99  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "settlement institution identification code", self::__LLVAR),
			100 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "receiving institution identification code", self::__LLVAR),
			101 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   17, "file name", self::__LLVAR),
			102 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   28, "account identification 1", self::__LLVAR),
			103 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   28, "account identification 2", self::__LLVAR),
			104 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  100, "transaction description", self::__LLLVAR),

			105 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			106 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			107 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			108 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			109 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			110 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			111 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			112 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),

			113 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			114 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			115 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			116 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			117 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			118 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			119 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			120 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),

			121 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			122 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			123 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			124 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			125 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			126 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			127 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			128 => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,  64, "message authentication code"),
		];
	}

	// Return fields info for ISO8583:1993.
	private function getISO8583_1993()
	{
		return [
			1   => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "secondary bitmap (optional)"),
			2   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   19, "primary account number", self::__LLVAR),
			3   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "processing code"),
			4   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, transaction"),
			5   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, reconciliation"),
			6   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "amount, cardholder biling"),
			7   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "date and time, transmission", self::__MMDDYYHHMMSS),
			8   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "amount, cardholder biling fee"),

			9   => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "conversion rate, reconciliation"),
			10  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "conversion rate, cardholder biling"),
			11  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "system trace audit number"),
			12  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  12, "date and time, local transaction", self::__MMDDYYHHMMSS),
			13  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, effective", self::__YYMM),
			14  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, expiration", self::__YYMM),
			15  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "date, settlement", self::__YYMMDD),
			16  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, conversion", self::__MMDD),

			17  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "date, capture", self::__MMDD),
			18  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "merchant type"),
			19  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, acquiring institution"),
			20  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, primary account number"),
			21  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, forwarding institution"),
			22  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,  12, "point of service data code"),
			23  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "card sequence number"),
			24  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "function code"),

			25  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "message reason code"),
			26  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   4, "card receptor business code"),
			27  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   1, "approval code length"),
			28  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "date, reconciliation", self::__YYMMDD),
			29  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "reconciliation indicator"),
			30  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  24, "amount original"),
			31  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   99, "acquirer reference data", self::__LLVAR),
			32  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "acquirer institution identification code", self::__LLVAR),

			33  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "fowarding institution identification code", self::__LLVAR),
			34  => self::mountFieldInfo(self::__NS,  self::VARIABLE_FIELD_TRUE,   28, "primary account number, extended", self::__LLVAR),
			35  => self::mountFieldInfo(self::__Z,   self::VARIABLE_FIELD_FALSE,  37, "track 2 data", self::__LLVAR),
			36  => self::mountFieldInfo(self::__Z,   self::VARIABLE_FIELD_FALSE, 104, "track 3 data", self::__LLLVAR),
			37  => self::mountFieldInfo(self::__ANP, self::VARIABLE_FIELD_FALSE,  12, "retrieval reference number"),
			38  => self::mountFieldInfo(self::__ANP, self::VARIABLE_FIELD_FALSE,   6, "approval code"),
			39  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "action code"),
			40  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "service code"),

			41  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,   8, "card acceptor terminal idetification"),
			42  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,  15, "card acceptor identification code"),
			43  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   99, "card acceptor name/location", self::__LLVAR),
			44  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   99, "aditional response data", self::__LLVAR),
			45  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   76, "track 1 data", self::__LLVAR),
			46  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  204, "amounts, fees", self::__LLLVAR),
			47  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "additional data, national", self::__LLLVAR),
			48  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "additional data, private", self::__LLLVAR),

			49  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, transaction"),
			50  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, reconciliation"),
			51  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_FALSE,   3, "currency code, cardholder biling"),
			52  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "personal identification number (PIN) data"),
			53  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_TRUE,   48, "security related control information", self::__LLVAR),
			54  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  120, "amounts, additional", self::__LLLVAR),
			55  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_TRUE,  255, "integrated circuit card system related data", self::__LLLVAR),
			56  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   35, "original data elements", self::__LLVAR),

			57  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "authorization life cycle code"),
			58  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "authorizing agent institution identification code", self::__LLVAR),
			59  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "transport data", self::__LLVAR),
			60  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			61  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			62  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			63  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reserved for national use", self::__LLLVAR),
			64  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "message authentication code field"),

			65  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "reserved for ISO use"),
			66  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  204, "amounts, origial fees", self::__LLLVAR),
			67  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   2, "extended payment data"),
			68  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, receiving institution"),
			69  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, settlement institution"),
			70  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, authorizing agent institution"),
			71  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   8, "message number"),
			72  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "data record", self::__LLLVAR),

			73  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   6, "date, action", self::__YYMMDD),
			74  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "credits, number"),
			75  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "credits, reversal number"),
			76  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "debits, number"),
			77  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "debits, reversal number"),
			78  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "transfer number"),
			79  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "transfer, reversal number"),
			80  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "inquiries, number"),

			81  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "authorizations, number"),
			82  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "inquiries, reversal number"),
			83  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "payments, number"),
			84  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "payments, reversal number"),
			85  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "fee collections, number"),
			86  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "credits, amount"),
			87  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "credits, reversal amount"),
			88  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "debits, amount"),

			89  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "debits, reversal amount"),
			90  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "authorizations, reversal number"),
			91  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, transaction destination institution"),
			92  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,   3, "country code, transaction originator institution"),
			93  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "transaction destination institution identification code", self::__LLVAR),
			94  => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "transaction originator institution identification code", self::__LLVAR),
			95  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   99, "card issuer reference data", self::__LLVAR),
			96  => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_TRUE,  999, "key management data", self::__LLLVAR),

			97  => self::mountFieldInfo(self::__XN,  self::VARIABLE_FIELD_FALSE,  16, "amount, net reconciliation"),
			98  => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_FALSE,  25, "payee"),
			99  => self::mountFieldInfo(self::__AN,  self::VARIABLE_FIELD_TRUE,   11, "settlement institution identification code", self::__LLVAR),
			100 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_TRUE,   11, "receiving institution identification code", self::__LLVAR),
			101 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   17, "file name", self::__LLVAR),
			102 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   28, "account identification 1", self::__LLVAR),
			103 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   28, "account identification 2", self::__LLVAR),
			104 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  100, "transaction description", self::__LLLVAR),

			105 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "credits, chargeback amount"),
			106 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  16, "debits, chargeback amount"),
			107 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "credits, chargeback number"),
			108 => self::mountFieldInfo(self::__N,   self::VARIABLE_FIELD_FALSE,  10, "debits, chargeback number"),
			109 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   84, "credits, fee amounts", self::__LLVAR),
			110 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,   84, "debits, fee amounts", self::__LLVAR),
			111 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			112 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),

			113 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			114 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			115 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for ISO use", self::__LLLVAR),
			116 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			117 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			118 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			119 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			120 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),

			121 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			122 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for national use", self::__LLLVAR),
			123 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			124 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			125 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			126 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			127 => self::mountFieldInfo(self::__ANS, self::VARIABLE_FIELD_TRUE,  999, "reversed for private use", self::__LLLVAR),
			128 => self::mountFieldInfo(self::__B,   self::VARIABLE_FIELD_FALSE,   8, "message authentication code field"),
		];
	}

	// Return fields info for ISO8583:2003.
	private function getISO8583_2003()
	{
		return [];
	}
}
