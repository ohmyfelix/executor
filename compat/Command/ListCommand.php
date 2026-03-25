<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Command;

use Contributte\Executor\Command\ListCommand as ExecutorListCommand;
use Contributte\Scheduler\IScheduler;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
	name: 'scheduler:list',
	aliases: ['executor:list'],
	description: 'List all scheduler jobs'
)]
class ListCommand extends ExecutorListCommand
{

	public function __construct(IScheduler $scheduler)
	{
		parent::__construct($scheduler);
	}

}
