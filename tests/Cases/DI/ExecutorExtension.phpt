<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Contributte\Executor\DI\ExecutorExtension;
use Contributte\Executor\IExecutor;
use Contributte\Scheduler\IScheduler as LegacyScheduler;
use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Nette\DI\Compiler;
use Nette\DI\Extensions\InjectExtension;
use Tester\Assert;
use Tests\Fixtures\InjectableJob;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('executor', new ExecutorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			services:
				callbackJob: Tests\Fixtures\CallbackJob
				scheduledJob: Tests\Fixtures\CustomJob

			executor:
				jobs:
					- {cron: '* * * * *', callback: Tests\Fixtures\CallbackJob::foo}
					- {cron: '* * * * *', callback: [@callbackJob, bar]}
					- Tests\Fixtures\CustomJob
					- @scheduledJob
			NEON
			));
		})->build();

	$executor = $container->getByType(IExecutor::class);
	$legacyExecutor = $container->getByType(LegacyScheduler::class);

	Assert::type(IExecutor::class, $executor);
	Assert::type(LegacyScheduler::class, $legacyExecutor);
	Assert::count(4, $executor->getAll());
});

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('executor', new ExecutorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			executor:
				jobs:
					myJob: {class: Tests\Fixtures\CustomJob}
			NEON
			));
		})->build();

	$executor = $container->getByType(IExecutor::class);
	Assert::type(IExecutor::class, $executor);
	Assert::count(1, $executor->getAll());
});

Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('executor', new ExecutorExtension());
			$compiler->addExtension('inject', new InjectExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
			services:
				dependency: Tests\Fixtures\SomeDependency

			executor:
				jobs:
					injectableJob: {class: Tests\Fixtures\InjectableJob, inject: true}
			NEON
			));
		})->build();

	$executor = $container->getByType(IExecutor::class);
	Assert::type(IExecutor::class, $executor);
	Assert::count(1, $executor->getAll());

	$jobs = $executor->getAll();
	$job = reset($jobs);
	Assert::type(InjectableJob::class, $job);
	Assert::notNull($job->getDependency());
	Assert::same('injected', $job->getDependency()->getValue());
});
