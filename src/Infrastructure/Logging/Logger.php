<?php

namespace Chess\Infrastructure\Logging;

use Chess\Infrastructure\LogLevel;
use Stringable;

class Logger
{
	public static function log(Stringable|string $message, LogLevel $level = LogLevel::DEBUG): void
	{
		//TODO: Implement proper logging

		echo "<$level->value> " . $message . "\n";

	}
}