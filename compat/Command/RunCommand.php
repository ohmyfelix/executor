<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Command;

use Contributte\Executor\Command\RunCommand as ExecutorRunCommand;
use Contributte\Scheduler\IScheduler;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
	name: 'scheduler:run',
	aliases: ['executor:run'],
	description: 'Run scheduler jobs'
)]
class RunCommand extends ExecutorRunCommand
{

	public function __construct(IScheduler $scheduler)
	{
		parent::__construct($scheduler);
	}

}
