<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Helpers;

use Contributte\Executor\Helpers\Debugger as ExecutorDebugger;

class_exists(ExecutorDebugger::class);
class_alias(ExecutorDebugger::class, __NAMESPACE__ . '\\Debugger');
