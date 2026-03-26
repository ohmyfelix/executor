<?php declare(strict_types = 1);

namespace Contributte\Executor\DI;

use Contributte\Executor\CallbackJob;
use Contributte\Executor\Command\ForceRunCommand;
use Contributte\Executor\Command\HelpCommand;
use Contributte\Executor\Command\ListCommand;
use Contributte\Executor\Command\RunCommand;
use Contributte\Executor\Executor;
use Contributte\Executor\IExecutor;
use Contributte\Executor\LockingExecutor;
use InvalidArgumentException;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\DI\Extensions\InjectExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class ExecutorExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'path' => Expect::string()->nullable(),
			'jobs' => Expect::arrayOf(
				Expect::anyOf(Expect::string(), Expect::array())
			),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$executorDefinition = $builder->addDefinition($this->prefix('executor'))
			->setType(IExecutor::class);
		if ($config->path !== null) {
			$executorDefinition->setFactory(LockingExecutor::class, [$config->path]);
		} else {
			$executorDefinition->setFactory(Executor::class);
		}

		$builder->addDefinition($this->prefix('runCommand'))
			->setFactory(RunCommand::class)
			->setAutowired(false);
		$builder->addDefinition($this->prefix('forceRunCommand'))
			->setFactory(ForceRunCommand::class)
			->setAutowired(false);
		$builder->addDefinition($this->prefix('listCommand'))
			->setFactory(ListCommand::class)
			->setAutowired(false);
		$builder->addDefinition($this->prefix('helpCommand'))
			->setFactory(HelpCommand::class)
			->setAutowired(false);

		// Jobs
		foreach ($config->jobs as $jobName => $jobConfig) {
			// 1. Array config
			if (is_array($jobConfig)) {
				// Callback job with cron expression
				if (isset($jobConfig['cron']) || isset($jobConfig['callback'])) {
					if (!isset($jobConfig['cron'], $jobConfig['callback'])) {
						throw new InvalidArgumentException(sprintf('Both options "callback" and "cron" of %s > jobs > %s must be configured', $this->name, $jobName));
					}

					$jobDefinition = new Statement(CallbackJob::class, [$jobConfig['cron'], $jobConfig['callback']]);
				} else {
					// Class config with optional inject
					$class = $jobConfig['class'] ?? null;
					if ($class === null || !is_string($class)) {
						throw new InvalidArgumentException(sprintf('Option "class" of %s > jobs > %s must be configured', $this->name, $jobName));
					}

					$inject = $jobConfig['inject'] ?? false;
					if (!is_bool($inject)) {
						throw new InvalidArgumentException(sprintf('Option "inject" of %s > jobs > %s must be boolean', $this->name, $jobName));
					}

					$jobDefinition = $builder->addDefinition($this->prefix('job.' . $jobName))
						->setFactory($class)
						->setAutowired(false);

					if ($inject) {
						$jobDefinition->addTag(InjectExtension::TagInject);
					}
				}
			// 2. String config (class name or service reference)
			} elseif (is_string($jobConfig)) {
				$jobDefinition = $builder->addDefinition($this->prefix('job.' . $jobName))
					->setFactory($jobConfig)
					->setAutowired(false);
			// 3. Invalid config
			} else {
				throw new InvalidArgumentException(sprintf('Job %s > jobs > %s must be a string or array', $this->name, $jobName));
			}

			$executorDefinition->addSetup('add', [$jobDefinition, $jobName]);
		}
	}

}
