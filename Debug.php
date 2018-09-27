<?php

class Debug {
	private static $instance;
	private static $isEnabled;

	private function __construct()
	{
		self::$instance = null;
		self::$isEnabled = false;
	}

	public static function getInstance()
	{
		if(empty(self::$instance))
		{
			self::$instance = new Debug();
		}

		return self::$instance;
	}

	// Print debug messages when it is enabled.
	public function printDebug($msg)
	{
		if(self::$isEnabled)
		{
			echo($msg);
		}
	}

	// Print debug messages all the time.
	public function print($msg)
	{
		echo($msg);
	}

	// Enable/disable debug prints.
	public function enableDebug()
	{
		self::$isEnabled = true;
	}

	// Enable/disable debug prints.
	public function disableDebug()
	{
		self::$isEnabled = false;
	}
}
