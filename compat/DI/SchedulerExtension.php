<?php declare(strict_types = 1);

namespace Contributte\Scheduler\DI;

use Contributte\Executor\DI\ExecutorExtension;

class SchedulerExtension extends ExecutorExtension
{

	protected function getPrimaryServiceName(): string
	{
		return 'scheduler';
	}

	protected function getSecondaryServiceName(): ?string
	{
		return 'executor';
	}

	protected function getExecutorClass(): string
	{
		return 'Contributte\\Scheduler\\Scheduler';
	}

	protected function getLockingExecutorClass(): string
	{
		return 'Contributte\\Scheduler\\LockingScheduler';
	}

	protected function getRunCommandClass(): string
	{
		return 'Contributte\\Scheduler\\Command\\RunCommand';
	}

	protected function getForceRunCommandClass(): string
	{
		return 'Contributte\\Scheduler\\Command\\ForceRunCommand';
	}

	protected function getListCommandClass(): string
	{
		return 'Contributte\\Scheduler\\Command\\ListCommand';
	}

	protected function getHelpCommandClass(): string
	{
		return 'Contributte\\Scheduler\\Command\\HelpCommand';
	}

}
