<?php declare(strict_types = 1);

namespace Tests\Cases\Command;

use Contributte\Executor\Command\ForceRunCommand as ExecutorForceRunCommand;
use Contributte\Executor\Command\HelpCommand as ExecutorHelpCommand;
use Contributte\Executor\Command\ListCommand as ExecutorListCommand;
use Contributte\Executor\Command\RunCommand as ExecutorRunCommand;
use Contributte\Scheduler\Command\ForceRunCommand as SchedulerForceRunCommand;
use Contributte\Scheduler\Command\HelpCommand as SchedulerHelpCommand;
use Contributte\Scheduler\Command\ListCommand as SchedulerListCommand;
use Contributte\Scheduler\Command\RunCommand as SchedulerRunCommand;
use Contributte\Tester\Toolkit;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	assertCommand(ExecutorRunCommand::class, 'executor:run', ['scheduler:run']);
	assertCommand(ExecutorForceRunCommand::class, 'executor:force-run', ['scheduler:force-run']);
	assertCommand(ExecutorListCommand::class, 'executor:list', ['scheduler:list']);
	assertCommand(ExecutorHelpCommand::class, 'executor:help', ['scheduler:help']);

	assertCommand(SchedulerRunCommand::class, 'scheduler:run', ['executor:run']);
	assertCommand(SchedulerForceRunCommand::class, 'scheduler:force-run', ['executor:force-run']);
	assertCommand(SchedulerListCommand::class, 'scheduler:list', ['executor:list']);
	assertCommand(SchedulerHelpCommand::class, 'scheduler:help', ['executor:help']);
});

/**
 * @param list<string> $aliases
 */
function assertCommand(string $className, string $name, array $aliases): void
{
	$attribute = (new ReflectionClass($className))->getAttributes(AsCommand::class)[0] ?? null;
	Assert::notNull($attribute);

	$arguments = $attribute->getArguments();
	Assert::same($name, $arguments['name'] ?? null);
	Assert::same($aliases, $arguments['aliases'] ?? []);
}
