<?php declare(strict_types = 1);

namespace Contributte\Scheduler;

use Contributte\Executor\ExpressionJob as ExecutorExpressionJob;

abstract class ExpressionJob extends ExecutorExpressionJob implements IJob
{

}
