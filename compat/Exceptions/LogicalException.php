<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Exceptions;

use Contributte\Executor\Exceptions\LogicalException as ExecutorLogicalException;

class_exists(ExecutorLogicalException::class);
class_alias(ExecutorLogicalException::class, __NAMESPACE__ . '\\LogicalException');
