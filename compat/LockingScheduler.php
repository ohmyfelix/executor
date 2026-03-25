<?php declare(strict_types = 1);

namespace Contributte\Scheduler;

use Contributte\Executor\LockingExecutor;

class LockingScheduler extends LockingExecutor implements IScheduler
{

}
