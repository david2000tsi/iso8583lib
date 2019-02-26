<?php

namespace ISO8583LIB;

require_once(__DIR__.'/../vendor/autoload.php');

class ISO8583
{
	// ISO Versions:
	const ISO8583_1987 = FieldsInfo::ISO8583_1987;
	const ISO8583_1993 = FieldsInfo::ISO8583_1993;
	const ISO8583_2003 = FieldsInfo::ISO8583_2003;

	// The bitmap can has 64 or 128 bits.
	// When the first bit (from de left) is set 1, soh there is a secondary bitmap.

	// Message type.
	private $mti;
	// We use for temporary primary and secondary bitmap (string with 128 bytes).
	private $bitmap;
	// Fields data.
	private $fieldsValue;
	// Generated message (after call generateMessage() method).
	private $msg;

	// Internal fields info instance.
	private $fieldsInfo;
	// Auto padding flag.
	private $autoPadding;
	// Check fields value content flag.
	private $checkFieldValueContent;
	// Success flag.
	private $success;

	// Initialize internal variables.
	private function init(string $isoVersion)
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
				$this->fieldsValue[$i] = "";
				$this->bitmap .= "0";
			}

			// Set auto padding true as default.
			$this->autoPadding = true;

			// Set check field value content false as default.
			$this->checkFieldValueContent = false;
		}
		else
		{
			Debug::getInstance()->printDebug("Failure, init() call.\n");
		}
	}

	// Class constructor.
	// To create an instance (to fill and generate a message) you should be pass a valid $isoVersion.
	// To decode a message the $isoMsg should be passed!
	public function __construct(string $isoVersion, string $isoMsg = "")
	{
		if(empty($isoMsg))
		{
			$this->init($isoVersion);
		}
		else
		{
			$decodeRes = $this->decodeMessage($isoVersion, $isoMsg);

			// Set manually success variable to sinalize decode success.
			$this->success = $decodeRes;
		}
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

	// Return debug current state.
	public function isEnabledDebug()
	{
		return Debug::getInstance()->isEnabledDebug();
	}

	// Enable auto padding according fields type. Set true as default.
	// When this option is enabled you can pass values with different lengths for fixed fields length, the module will be insert padding automatically.
	// When disabled you should be pass value with corrent length or will be occurrs an error.
	public function enableAutoPadding()
	{
		$this->autoPadding = true;
	}

	// Disable auto padding.
	public function disableAutoPadding()
	{
		$this->autoPadding = false;
	}

	// Return auto padding current state.
	public function isEnabledAutoPadding()
	{
		return $this->autoPadding;
	}

	// Enable check field value content.
	// When it is enabled, the function $this->addField() will be check the $value content according FieldsInfo table description.
	public function enableCheckFieldValueContent()
	{
		$this->checkFieldValueContent = true;
	}

	// Disable check field value content.
	public function disableCheckFieldValueContent()
	{
		$this->checkFieldValueContent = false;
	}

	// Return check field value content current state.
	public function isEnabledCheckFieldValueContent()
	{
		return $this->checkFieldValueContent;
	}

	// Check instance status after call constructor.
	// Return false case error os success if the instance was created successfully.
	public function success()
	{
		return $this->success;
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
			return $this->fieldsValue[$field - 1];
		}
		return false;
	}

	// Set field.
	public function addField(int $field, string $value)
	{
		if($this->fieldsInfo->isValidField($field) && $this->autoPadding)
		{
			$this->insertPadding($field, $value);
		}

		if($this->fieldsInfo->isValidFieldValue($field, $value, $this->checkFieldValueContent))
		{
			$this->fieldsValue[$field - 1] = $value;

			$this->updateBitmap($field);
			Debug::getInstance()->printDebug("Field (".$field.") -> [".$value."] added!\n");
			return true;
		}

		Debug::getInstance()->printDebug("Field (".$field.") -> [".$value."] could not be added!\n");
		return false;
	}

	// Unset field.
	public function removeField(int $field)
	{
		if($this->fieldsInfo->isValidField($field))
		{
			$value = $this->fieldsValue[$field - 1];
			$this->fieldsValue[$field - 1] = "";

			$this->updateBitmap($field, false);

			Debug::getInstance()->printDebug("Field (".$field.") -> [".$value."] removed!\n");
			return true;
		}

		Debug::getInstance()->printDebug("Field (".$field.") -> [".$value."] could not be removed!\n");
		return false;
	}

	// Insert padding according field info.
	// Case the field has fixed length this function insert padding according field type.
	// This function only will be called (by other) when the auto padding option is enabled.
	private function insertPadding(int $field, string &$value)
	{
		$valueLength = strlen($value);
		if(!$this->fieldsInfo->isValidField($field) || $valueLength == 0)
		{
			return false;
		}

		$fieldInfo = $this->fieldsInfo->getFieldInfo($field);

		if($fieldInfo["length"] < $valueLength)
		{
			return false;
		}

		if(!$fieldInfo["isVariableField"])
		{
			switch($fieldInfo["type"])
			{
				case FieldsInfo::__N:
				case FieldsInfo::__AN:
				case FieldsInfo::__NS:
				case FieldsInfo::__ANP:
				case FieldsInfo::__ANS:
					$value = str_pad($value, $fieldInfo["length"], '0', STR_PAD_LEFT);
					break;
				default:
					$value = str_pad($value, $fieldInfo["length"], ' ', STR_PAD_RIGHT);
					break;
			}
			return true;
		}
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
			$this->fieldsValue[0] = $this->getSecondaryBitmap();
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

		// Converting lowercase chars to uppercase...
		$this->msg = strtoupper($this->msg);

		Debug::getInstance()->printDebug("Message (".strlen($this->msg)."): [".$this->msg."]\n");

		return $this->msg;
	}

	// Returns a substring from original removing it from original string.
	private function getStringFromBeginningAndCleanFromOriginalString(string &$originalString, int $lenToRecovery, bool $getAvailableCaseOverflow = true)
	{
		$originalStringLen = strlen($originalString);

		if($originalStringLen < $lenToRecovery)
		{
			if($getAvailableCaseOverflow)
			{
				$lenToRecovery = $originalStringLen;
			}
			else
			{
				return false;
			}
		}

		$newString = substr($originalString, 0, $lenToRecovery); // Recovery substring from original string.
		$originalString = substr($originalString, $lenToRecovery); // Remove from original string.
		return $newString;
	}

	private function restoreBitmapFromHexString(string $bitmap)
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

	// Check if bitmap is valid, basically verify his length (each bitmap, 16 bytes hex string).
	// Returns false if not or true if is a valid bitmap.
	private function isValidBitmapHexString(string $bitmap)
	{
		return (strlen($bitmap) == 16);
	}

	// Decode the passed message.
	// Return false if the message is invalid.
	// Return true case success, the internal variables will be filled by message data.
	public function decodeMessage(string $isoVersion, string $isoMsg)
	{
		$fieldsIsoMsg = 0;
		$i = 0;
		$minimalIsoMsgLen64 = (4 + 16); // MTI + Primary bitmap.

		if(empty($isoMsg) || strlen($isoMsg) < $minimalIsoMsgLen64)
		{
			Debug::getInstance()->printDebug("Invalid ISO message...\n");
			return false;
		}

		// Converting lowercase chars to uppercase...
		$isoMsg = strtoupper($isoMsg);

		$this->init($isoVersion);

		if(!$this->success)
		{
			return false;
		}

		Debug::getInstance()->printDebug("Decoding ISO message...\n");
		$this->mti = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 4);
		if(!$this->fieldsInfo->isValidMti($this->mti))
		{
			Debug::getInstance()->printDebug("Invalid Mti -> ".$this->mti."\n");
			return false;
		}

		Debug::getInstance()->printDebug("Mti -> ".$this->mti."\n");

		$primaryBitmap = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 16);
		if(!$this->isValidBitmapHexString($primaryBitmap))
		{
			Debug::getInstance()->printDebug("Invalid primary bitmap -> ".$primaryBitmap."\n");
			return false;
		}

		$this->bitmap = $this->restoreBitmapFromHexString($primaryBitmap);
		$fieldsIsoMsg = FieldsInfo::BITMAP_LEN_BITS;

		Debug::getInstance()->printDebug("Primary bitmap (".strlen($primaryBitmap)."): [".$primaryBitmap."]\n");

		// If there is secondary bitmap soh we will recorery it..
		if($this->bitmap[0])
		{
			$secondaryBitmap = $this->getStringFromBeginningAndCleanFromOriginalString($isoMsg, 16);
			if(!$this->isValidBitmapHexString($secondaryBitmap))
			{
				Debug::getInstance()->printDebug("Invalid secondary bitmap -> ".$secondaryBitmap."\n");
				return false;
			}

			$this->bitmap .= $this->restoreBitmapFromHexString($secondaryBitmap);
			$this->fieldsValue[0] = $secondaryBitmap;
			$fieldsIsoMsg = FieldsInfo::NUM_FIELD_MAX;

			Debug::getInstance()->printDebug("Secondary bitmap (".strlen($primaryBitmap)."): [".$secondaryBitmap."]\n");
		}

		// Set $i to 1 because we need to skip the field 001 (used as secondary bitmap).
		for($i = 1; $i < $fieldsIsoMsg; $i++)
		{
			if($this->bitmap[$i])
			{
				// Has data in bitmap but has not enough data in the message.
				if(strlen($isoMsg) == 0)
				{
					break;
				}

				$realI = $i + 1;
				$sizeInt = 0;
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

				$this->fieldsValue[$i] = $value;

				// Validate length.
				$fieldRecoveredLen = strlen($this->fieldsValue[$i]);
				if($sizeInt != $fieldRecoveredLen)
				{
					Debug::getInstance()->printDebug("Field (".$realI.") -> Warning, bad ISO field: expected [".$sizeInt."] but got [".$fieldRecoveredLen."]! -> ".$this->fieldsValue[$i]."\n");
				}
				else
				{
					Debug::getInstance()->printDebug("Field (".$realI.") -> ".$this->fieldsValue[$i]."\n");
				}
			}
		}

		if($i != $fieldsIsoMsg)
		{
			Debug::getInstance()->printDebug("Failure, ISO message soo short!\n");
			return false;
		}

		Debug::getInstance()->printDebug("Decoded (".$fieldsIsoMsg." fields ISO message)!\n");
		return true;
	}
}
