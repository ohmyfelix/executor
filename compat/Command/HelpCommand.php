<?php declare(strict_types = 1);

namespace Contributte\Scheduler\Command;

use Contributte\Executor\Command\HelpCommand as ExecutorHelpCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
	name: 'scheduler:help',
	aliases: ['executor:help'],
	description: 'Print cron syntax'
)]
class HelpCommand extends ExecutorHelpCommand
{

}
