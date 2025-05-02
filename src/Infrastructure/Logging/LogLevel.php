<?php

namespace Chess\Infrastructure\Logging;

enum LogLevel: string
{
	case DEBUG = 'Debug';
	case INFO = 'Info';
	case WARNING = 'Warning';
	case ERROR = 'Error';
	case CRITICAL = "CRITICAL";
}
