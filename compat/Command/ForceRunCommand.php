<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Command;

use Contributte\Executor\Command\ForceRunCommand as ExecutorForceRunCommand;
use Contributte\Scheduler\IScheduler;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
	name: 'scheduler:force-run',
	aliases: ['executor:force-run'],
	description: 'Force run selected scheduler job'
)]
class ForceRunCommand extends ExecutorForceRunCommand
{

	public function __construct(IScheduler $scheduler)
	{
		parent::__construct($scheduler);
	}

}
