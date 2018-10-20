<?php

require_once(__DIR__.'/../vendor/autoload.php');

class Example
{
	private static $isoInstance;

	public static function run()
	{
		self::$isoInstance = new ISO8583(ISO8583::ISO8583_1987);

		if(!self::$isoInstance->success())
		{
			return false;
		}

		self::$isoInstance->enableDebug();

		echo("Debug ".(self::$isoInstance->isEnabledDebug() ? "enabled" : "disabled")."!\n");
		echo("Auto padding ".(self::$isoInstance->isEnabledAutoPadding() ? "enabled" : "disabled")."!\n");
		echo("Check field value content ".(self::$isoInstance->isEnabledCheckFieldValueContent() ? "enabled" : "disabled")."!\n");

		echo("Generate testing:\n");

		self::$isoInstance->setMti("0200");
		self::$isoInstance->addField( 2, "9999999999999999999");
		self::$isoInstance->addField( 4, "123456789012");
		self::$isoInstance->addField(10, "10");
		self::$isoInstance->addField(22, "12");
		self::$isoInstance->addField(31, "11111111");
		self::$isoInstance->addField(32, "7777777");
		self::$isoInstance->addField(34, "947654652576423534875345");
		self::$isoInstance->addField(36, "44441758497514729142975874528475924356724976542952475897342547328524387839457294553303486409624354354444");
		self::$isoInstance->addField(50, "222");
		self::$isoInstance->addField(72, "7777");
		self::$isoInstance->addField(76, "0987654321");
		self::$isoInstance->addField(95, "777437294863654765689476984987564264363567");

		$msgIso = self::$isoInstance->generateMessage();
		self::$isoInstance = null;

		echo("\nDecode testing:\n");

		// Handle message length (for tests).
		$msgIso = substr($msgIso, 0, strlen($msgIso));

		self::$isoInstance = new ISO8583(ISO8583::ISO8583_1987, $msgIso);
		if(self::$isoInstance->success())
		{
			echo("\n");
			echo("Field 02: ".self::$isoInstance->getField( 2)."\n");
			echo("Field 04: ".self::$isoInstance->getField( 4)."\n");
			echo("Field 10: ".self::$isoInstance->getField(10)."\n");
			echo("Field 22: ".self::$isoInstance->getField(22)."\n");
			echo("Field 31: ".self::$isoInstance->getField(31)."\n");
			echo("Field 32: ".self::$isoInstance->getField(32)."\n");
			echo("Field 34: ".self::$isoInstance->getField(34)."\n");
			echo("Field 36: ".self::$isoInstance->getField(36)."\n");
			echo("Field 50: ".self::$isoInstance->getField(50)."\n");
			echo("Field 72: ".self::$isoInstance->getField(72)."\n");
			echo("Field 76: ".self::$isoInstance->getField(76)."\n");
			echo("Field 95: ".self::$isoInstance->getField(95)."\n");
		}

		return self::$isoInstance->success();
	}
}

Example::run();
