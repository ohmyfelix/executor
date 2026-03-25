<?php declare(strict_types = 1);

namespace Tests\Cases\Command;

use Contributte\Executor\Command\ForceRunCommand as ExecutorForceRunCommand;
use Contributte\Executor\Command\HelpCommand as ExecutorHelpCommand;
use Contributte\Executor\Command\ListCommand as ExecutorListCommand;
use Contributte\Executor\Command\RunCommand as ExecutorRunCommand;
use Contributte\Tester\Toolkit;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	assertCommand(ExecutorRunCommand::class, 'executor:run');
	assertCommand(ExecutorForceRunCommand::class, 'executor:force-run');
	assertCommand(ExecutorListCommand::class, 'executor:list');
	assertCommand(ExecutorHelpCommand::class, 'executor:help');
});

function assertCommand(string $className, string $name): void
{
	$attribute = (new ReflectionClass($className))->getAttributes(AsCommand::class)[0] ?? null;
	Assert::notNull($attribute);

	$arguments = $attribute->getArguments();
	Assert::same($name, $arguments['name'] ?? null);
	Assert::same([], $arguments['aliases'] ?? []);
}
