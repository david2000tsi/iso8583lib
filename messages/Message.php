<?php

abstract class Message
{
	protected $isoInstance;
	protected $success;

	abstract public function __construct(string $isoMsg = "");

	abstract public function getMessage();

	public function success()
	{
		return $this->success;
	}
}
