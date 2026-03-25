<?php declare(strict_types = 1);

namespace Contributte\Executor\Command;

use Contributte\Executor\IExecutor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'executor:run',
	aliases: ['scheduler:run'],
	description: 'Run executor jobs'
)]
class RunCommand extends Command
{

	private IExecutor $executor;

	public function __construct(IExecutor $executor)
	{
		parent::__construct();

		$this->executor = $executor;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->executor->run();

		return Command::SUCCESS;
	}

}
