<?php

require_once('ISO8583.php');

$iso = new ISO8583(ISO8583::ISO8583_1987);

if(!$iso->success())
{
	return;
}

$iso->enableDebug();

echo("Generate testing:\n");

$iso->setMti("0200");
$iso->addField( 2, "9999999999999999999");
$iso->addField( 4, "123456789012");
$iso->addField(10, "88888888");
$iso->addField(31, "11111111");
$iso->addField(32, "7777777");
$iso->addField(34, "947654652576423534875345");
$iso->addField(36, "44441758497514729142975874528475924356724976542952475897342547328524387839457294553303486409624354354444");
$iso->addField(50, "222");
$iso->addField(72, "7777");
$iso->addField(76, "0987654321");
$iso->addField(95, "777437294863654765689476984987564264363567");

$msgIso = $iso->generateMessage();
$iso = null;

echo("\nDecode testing:\n");


// Handle message length (for tests).
$msgIso = substr($msgIso, 0, strlen($msgIso));

$iso = new ISO8583(ISO8583::ISO8583_1987, $msgIso);
if($iso->success())
{
	echo("\n");
	echo("Field 10: ".$iso->getField(10)."\n");
	echo("Field 34: ".$iso->getField(34)."\n");
	echo("Field 50: ".$iso->getField(50)."\n");
	echo("Field 76: ".$iso->getField(76)."\n");
	echo("Field 95: ".$iso->getField(95)."\n");
}
else
{
	echo("Failure!\n");
}
