<?php declare(strict_types = 1);

namespace Contributte\Executor\Helpers;

use Throwable;
use Tracy\Debugger as TracyDebugger;

class Debugger
{

	public static function log(Throwable $e): void
	{
		if (class_exists(TracyDebugger::class)) {
			TracyDebugger::log($e);
		}
	}

}
