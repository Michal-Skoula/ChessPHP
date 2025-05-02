<?php

namespace Chess\Infrastructure;

enum LogLevel: string
{
	case DEBUG = 'Debug';
	case INFO = 'Info';
	case WARNING = 'Warning';
	case ERROR = 'Error';
	case CRITICAL = "CRITICAL";
}
