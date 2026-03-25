<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Exceptions;

use Contributte\Executor\Exceptions\RuntimeException as ExecutorRuntimeException;

class_exists(ExecutorRuntimeException::class);
class_alias(ExecutorRuntimeException::class, __NAMESPACE__ . '\\RuntimeException');
