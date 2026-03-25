<?php declare(strict_types = 1);

namespace Contributte\Executor\Command;

use Contributte\Executor\IExecutor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'executor:force-run',
	description: 'Force run selected executor job'
)]
class ForceRunCommand extends Command
{

	private IExecutor $executor;

	public function __construct(IExecutor $executor)
	{
		parent::__construct();

		$this->executor = $executor;
	}

	protected function configure(): void
	{
		$this->addArgument('key', InputArgument::REQUIRED, 'Job key');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$key = $input->getArgument('key');

		if (!is_string($key) && !is_int($key)) {
			return Command::FAILURE;
		}

		$job = $this->executor->get($key);

		if ($job === null) {
			return Command::FAILURE;
		}

		$job->run();

		return Command::SUCCESS;
	}

}
