<?php

require('FieldsInfo.php');
require('ISO8583.php');
require('Debug.php');

$iso = new ISO8583(ISO8583::ISO8583_1987);

if(!$iso->success())
{
	return;
}

$iso->enableDebug();

echo("Generate testing:\n");

$iso->setMti("0200");
$iso->addField( 2, "9999999999999999999");
$iso->addField(10, "88888888");
$iso->addField(31, "11111111");
$iso->addField(32, "7777777");
$iso->addField(34, "947654652576423534875345");
$iso->addField(50, "222");
$iso->addField(72, "7777");

$msgIso = $iso->generateMessage();

echo("\nDecode testing:\n");

$iso->decodeMessage($msgIso, ISO8583::ISO8583_1987);
echo("\n");
echo("Field 10: ".$iso->getField(10)."\n");
echo("Field 34: ".$iso->getField(34)."\n");
