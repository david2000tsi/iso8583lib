<?php

require_once('FieldsInfo.php');
require_once('Debug.php');

class ISO8583
{
	// ISO Versions:
	const ISO8583_1987 = FieldsInfo::ISO8583_1987;
	const ISO8583_1993 = FieldsInfo::ISO8583_1993;
	const ISO8583_2003 = FieldsInfo::ISO8583_2003;

	// The bitmap can has 64 or 128 bits.
	// When the first bit (from de left) is set 1, soh there is a secondary bitmap.

	private $mti;
	private $bitmap; // We use for temporary primary and secondary bitmap (string with 128 bytes).
	private $msg;

	// Internal fields info instance.
	private $fieldsInfo;
	private $success;

	private $field_001; // Used as second bitmap.
	private $field_002;
	private $field_003;
	private $field_004;
	private $field_005;
	private $field_006;
	private $field_007;
	private $field_008;

	private $field_009;
	private $field_010;
	private $field_011;
	private $field_012;
	private $field_013;
	private $field_014;
	private $field_015;
	private $field_016;

	private $field_017;
	private $field_018;
	private $field_019;
	private $field_020;
	private $field_021;
	private $field_022;
	private $field_023;
	private $field_024;

	private $field_025;
	private $field_026;
	private $field_027;
	private $field_028;
	private $field_029;
	private $field_030;
	private $field_031;
	private $field_032;

	private $field_033;
	private $field_034;
	private $field_035;
	private $field_036;
	private $field_037;
	private $field_038;
	private $field_039;
	private $field_040;

	private $field_041;
	private $field_042;
	private $field_043;
	private $field_044;
	private $field_045;
	private $field_046;
	private $field_047;
	private $field_048;

	private $field_049;
	private $field_050;
	private $field_051;
	private $field_052;
	private $field_053;
	private $field_054;
	private $field_055;
	private $field_056;

	private $field_057;
	private $field_058;
	private $field_059;
	private $field_060;
	private $field_061;
	private $field_062;
	private $field_063;
	private $field_064;

	private $field_065;
	private $field_066;
	private $field_067;
	private $field_068;
	private $field_069;
	private $field_070;
	private $field_071;
	private $field_072;

	private $field_073;
	private $field_074;
	private $field_075;
	private $field_076;
	private $field_077;
	private $field_078;
	private $field_079;
	private $field_080;

	private $field_081;
	private $field_082;
	private $field_083;
	private $field_084;
	private $field_085;
	private $field_086;
	private $field_087;
	private $field_088;

	private $field_089;
	private $field_090;
	private $field_091;
	private $field_092;
	private $field_093;
	private $field_094;
	private $field_095;
	private $field_096;

	private $field_097;
	private $field_098;
	private $field_099;
	private $field_100;
	private $field_101;
	private $field_102;
	private $field_103;
	private $field_104;

	private $field_105;
	private $field_106;
	private $field_107;
	private $field_108;
	private $field_109;
	private $field_110;
	private $field_111;
	private $field_112;

	private $field_113;
	private $field_114;
	private $field_115;
	private $field_116;
	private $field_117;
	private $field_118;
	private $field_119;
	private $field_120;

	private $field_121;
	private $field_122;
	private $field_123;
	private $field_124;
	private $field_125;
	private $field_126;
	private $field_127;
	private $field_128;

	private function init($isoVersion)
	{
		$this->mti = "";
		$this->bitmap = "";
		$this->msg = "";

		$this->fieldsInfo = new FieldsInfo($isoVersion);
		$this->success = $this->fieldsInfo->success();

		if($this->success)
		{
			for($i = 0; $i < FieldsInfo::NUM_FIELD_MAX; $i++)
			{
				$_field = self::getFieldVar($i + 1);
				$this->$_field = "";

				$this->bitmap .= "0";
			}
		}
	}

	public function __construct($isoVersion)
	{
		$this->init($isoVersion);
	}

	// Enable additional debug messages.
	public function enableDebug()
	{
		Debug::getInstance()->enableDebug();
	}

	// Disable additional debug messages.
	public function disableDebug()
	{
		Debug::getInstance()->disableDebug();
	}

	// Check instance status after call constructor.
	// Return false case error os success if the instance was created successfully.
	public function success()
	{
		return $this->success;
	}

	// Get internal field variable name.
	private function getFieldVar(int $field)
	{
		return sprintf('$field_%03d', $field);
	}

	// Update the bitmap according informed field number.
	private function updateBitmap(int $field, bool $addField = true)
	{
		$this->bitmap[($field - 1)] = (string) intval($addField);

		if($field > FieldsInfo::BITMAP_LEN_BITS)
		{
			$this->bitmap[0] = "1";
		}
	}

	// Convert bitmap to hex string.
	private function getHexStringBitmap()
	{
		$finalBitmap = "";

		// Split bitmap in 16 parts.
		$bitmapArray = array(
			substr($this->bitmap,   0, 8),
			substr($this->bitmap,   8, 8),
			substr($this->bitmap,  16, 8),
			substr($this->bitmap,  24, 8),
			substr($this->bitmap,  32, 8),
			substr($this->bitmap,  40, 8),
			substr($this->bitmap,  48, 8),
			substr($this->bitmap,  56, 8),
			substr($this->bitmap,  64, 8),
			substr($this->bitmap,  72, 8),
			substr($this->bitmap,  80, 8),
			substr($this->bitmap,  88, 8),
			substr($this->bitmap,  96, 8),
			substr($this->bitmap, 104, 8),
			substr($this->bitmap, 112, 8),
			substr($this->bitmap, 120, 8),
		);

		foreach($bitmapArray as $binaryPart)
		{
			// Recover decimal integer from binary string.
			$integerValue = bindec($binaryPart);

			// Convert decimal value to hex string.
			$tmpBitmap = sprintf("%02x", $integerValue);
			$finalBitmap .= $tmpBitmap;
		}

		return $finalBitmap;
	}

	// Return primary bitmap, 16 bytes hex string data.
	private function getPrimaryBitmap()
	{
		$primaryBitmap = substr($this->getHexStringBitmap($this->bitmap), 0, 16);

		Debug::getInstance()->printDebug("Primary Bitmap (".strlen($primaryBitmap)."): [".$primaryBitmap."]\n");

		return $primaryBitmap;
	}

	// Return secondary bitmap, 16 bytes hex string data.
	private function getSecondaryBitmap()
	{
		$secondaryBitmap = substr($this->getHexStringBitmap($this->bitmap), 16, 16);

		Debug::getInstance()->printDebug("Secondary Bitmap (".strlen($secondaryBitmap)."): [".$secondaryBitmap."]\n");

		return $secondaryBitmap;
	}

	// Set the mti header.
	public function setMti(string $mti)
	{
		if($this->fieldsInfo->isValidMti($mti))
		{
			$this->mti = $mti;
			return true;
		}

		return false;
	}

	// Retrieve field value.
	public function getField(int $field)
	{
		if($this->fieldsInfo->isValidField($field))
		{
			$_field = self::getFieldVar($field);
			return $this->$_field;
		}
		return false;
	}

	// Set field.
	public function addField(int $field, string $value)
	{
		if($this->fieldsInfo->isValidFieldValue($field, $value))
		{
			$_field = self::getFieldVar($field);
			$this->$_field = $value;

			$this->updateBitmap($field);
			Debug::getInstance()->printDebug($_field." -> ".$value.", added!\n");
			return true;
		}

		Debug::getInstance()->printDebug(":field_".$field." -> ".$value.", could not be added!\n");
		return false;
	}

	// Unset field.
	public function removeField(int $field)
	{
		if($this->fieldsInfo->isValidField($field))
		{
			$_field = self::getFieldVar($field);
			$this->$_field = "";

			$this->updateBitmap($field, false);

			Debug::getInstance()->printDebug($_field." -> ".$value.", could not be removed!\n");
			return true;
		}

		Debug::getInstance()->printDebug(":field_".$field." -> ".$value.", removed!\n");
		return false;
	}

	// Generate message according filled fields.
	public function generateMessage()
	{
		if(empty($this->mti) || empty($this->bitmap))
		{
			return false;
		}

		$this->msg = $this->mti;

		// Copy primary bitmap to message (16 bytes).
		$this->msg .= $this->getPrimaryBitmap();

		// If there is secondary bitmap soh we will also copy it to message (more 16 bytes, replacing field 001).
		if($this->bitmap[0])
		{
			$this->addField(1, $this->getSecondaryBitmap());
		}

		// Lets go to read all fields...
		for($i = 0; $i < FieldsInfo::NUM_FIELD_MAX; $i++)
		{
			if($this->bitmap[$i])
			{
				$realI = $i + 1;
				$msg = $this->getField($realI);

				if($this->fieldsInfo->isVariableField($realI))
				{
					$sizeLength = $this->fieldsInfo->getSizeOfLengthVariableField($realI);
					$format = sprintf("%%0%dd", $sizeLength);
					$this->msg .= sprintf($format, strlen($msg));
				}

				$this->msg .= $msg;
			}
		}

		Debug::getInstance()->printDebug("Message (".strlen($this->msg)."): [".$this->msg."]\n");

		// Converting lowercase chars to uppercase...
		$this->msg = strtoupper($this->msg);

		return $this->msg;
	}

	// Returns a substring from original removing it from original string.
	private function getStringFromBeginningAndCleanFromOriginalString(string &$originalString, int $lenToRecovery)
	{
		if(strlen($originalString) < $lenToRecovery)
		{
			return false;
		}

		$newString = substr($originalString, 0, $lenToRecovery); // Recovery substring from original string.
		$originalString = substr($originalString, $lenToRecovery); // Remove from original string.
		return $newString;
	}

	private function restoreBitmapFromHexString($bitmap)
	{
		$finalBitmap = "";

		// We will process each bitmap part (16 bytes).
		if(strlen($bitmap) != 16)
		{
			return "";
		}

		// Split bitmap hex string in 8 parts (each part with 2 bytes).
		$bitmapArray = array(
			substr($bitmap,  0, 2),
			substr($bitmap,  2, 2),
			substr($bitmap,  4, 2),
			substr($bitmap,  6, 2),
			substr($bitmap,  8, 2),
			substr($bitmap, 10, 2),
			substr($bitmap, 12, 2),
			substr($bitmap, 14, 2),
		);

		foreach($bitmapArray as $hexStringPart)
		{
			// Recover decimal integer from hex string.
			$integerValue = hexdec($hexStringPart);

			// Convert decimal value to binary string.
			$tmpBinString = decbin($integerValue);

			// Insert padding '0' to length to be equals to '8' (one byte).
			$finalBitmap .= str_pad($tmpBinString, 8, "0", STR_PAD_LEFT);

		}

		return $finalBitmap;
	}

	public function decodeMessage(string $isoMsg, $isoVersion)
	{
		$fieldsIsoMsg = 0;

		if(empty($isoMsg))
		{
			return false;
		}

		$this->init($isoVersion);

		Debug::getInstance()->printDebug("Decoding ISO message...\n");

		$this->mti = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 4);

		Debug::getInstance()->printDebug("Mti -> ".$this->mti."\n");

		$primaryBitmap = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 16);
		$this->bitmap = $this->restoreBitmapFromHexString($primaryBitmap);
		$fieldsIsoMsg = FieldsInfo::BITMAP_LEN_BITS;

		Debug::getInstance()->printDebug("Primary bitmap (".strlen($primaryBitmap)."): [".$primaryBitmap."]\n");

		// If there is secondary bitmap soh we will recorery it..
		if($this->bitmap[0])
		{
			$secondaryBitmap = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 16);
			$this->bitmap .= $this->restoreBitmapFromHexString($secondaryBitmap);
			$this->field_001 = $secondaryBitmap;
			$fieldsIsoMsg = FieldsInfo::NUM_FIELD_MAX;

			Debug::getInstance()->printDebug("Secondary bitmap (".strlen($primaryBitmap)."): [".$secondaryBitmap."]\n");
		}

		// Set $i to 1 because we need to skip the field 001 (used as secondary bitmap).
		for($i = 1; $i < $fieldsIsoMsg; $i++)
		{
			if($this->bitmap[$i])
			{
				$realI = $i + 1;
				$_field = self::getFieldVar($realI);
				$value = "";

				if($this->fieldsInfo->isVariableField($realI))
				{
					$sizeQtyChar = $this->fieldsInfo->getSizeOfLengthVariableField($realI);
					$sizeInt = (int) $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, $sizeQtyChar);

					$value = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, $sizeInt);
				}
				else
				{
					$fieldInfo = $this->fieldsInfo->getFieldInfo($realI);
					$sizeInt = $fieldInfo["length"];
					$value = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, $sizeInt);
				}

				$this->$_field = $value;
				Debug::getInstance()->printDebug($_field." -> ".$this->$_field."\n");
			}
		}

		Debug::getInstance()->printDebug("Decoded (".$fieldsIsoMsg." fields ISO message)!\n");
		return true;
	}
}
