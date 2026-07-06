![](https://heatbadger.now.sh/github/readme/contributte/executor/)

<p align=center>
  <a href="https://github.com/contributte/executor/actions"><img src="https://badgen.net/github/checks/contributte/executor/master"></a>
  <a href="https://codecov.io/gh/contributte/executor"><img src="https://badgen.net/codecov/c/github/contributte/executor"></a>
  <a href="https://packagist.org/packages/contributte/executor"><img src="https://badgen.net/packagist/dm/contributte/executor"></a>
  <a href="https://packagist.org/packages/contributte/executor"><img src="https://badgen.net/packagist/v/contributte/executor"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/executor"><img src="https://badgen.net/packagist/php/contributte/executor"></a>
  <a href="https://github.com/contributte/executor"><img src="https://badgen.net/github/license/contributte/executor"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Run scheduled PHP callbacks from Nette applications using cron expressions.

## Versions

| State       | Version | Branch   | Nette | PHP     |
|-------------|---------|----------|-------|---------|
| dev         | `^0.10` | `master` | 3.2+  | `>=8.2` |
| stable      | `^0.9`  | `master` | 3.2+  | `>=8.2` |

## Installation

To install latest version of `contributte/executor` use [Composer](https://getcomposer.org).

```bash
composer require contributte/executor
```

## Setup

Register extension.

```neon
extensions:
	executor: Contributte\Executor\DI\ExecutorExtension
```

## Configuration

Set up crontab. Use the `executor:run` command.

```
* * * * * php path-to-project/console executor:run
```

Optionally, you can set a temp path for storing lock files.

```neon
executor:
	path: '%tempDir%/executor'
```

## Jobs

This package defines 2 types of jobs:

- callback job
- service job

### Callback Job

Register your callbacks under `executor.jobs` key.

```neon
services:
	stats: App\Model\Stats

executor:
	jobs:
		# stats must be registered as service and have method calculate
		- { cron: '* * * * *', callback: [ @stats, calculate ] }

		# monitor is class with static method echo
		- { cron: '*/2 * * * *', callback: App\Model\Monitor::echo }
```

Be careful with cron syntax, take a look at following example. You can also validate your cron
using [crontab.guru](https://crontab.guru).

```
	*	*	*	*	*
	-	-	-	-	-
	|	|	|	|	|
	|	|	|	|	|
	|	|	|	|	+----- day of week (0 - 7) (Sunday=0 or 7)
	|	|	|	+---------- month (1 - 12)
	|	|	+--------------- day of month (1 - 31)
	|	+-------------------- hour (0 - 23)
	+------------------------- min (0 - 59)
```

### Custom Job

Create new class which implements `IJob` interface.

```php
use Contributte\Executor\IJob;

class ScheduledJob implements IJob
{

	private $dateService;

	private $statisticsService;

	public function __construct($dateService, $statisticsService) {
		$this->dateService = $dateService;
		$this->statisticsService = $statisticsService;
	}

	public function isDue(DateTime $dateTime): bool
	{
		if ($this->dateService->isRightTime($dateTime)) {
			return true;
		}
		return false;
	}

	public function run(): void
	{
		$this->statisticsService->calculate();
	}

}

```

Register your class into `config.neon` as regular services
into [nette dependency-injection container](https://doc.nette.org/en/3.0/dependency-injection).

```neon
executor:
	jobs:
		- App\Model\ScheduledJob
		- App\Model\OtherScheduledJob
```

You can also reference already registered service.

```neon
services:
	scheduledJob: App\Model\ScheduledJob

executor:
	jobs:
		- @scheduledJob
```

### Job With Inject Support

If your job class uses `inject*` methods for dependency injection, you can enable auto-injection using the `inject` option:

```neon
executor:
	jobs:
		myJob: {class: App\Model\ScheduledJob, inject: true}
```

This will automatically call all `inject*` methods on the job class after instantiation.

## Console

This package relies on `symfony/console`, use prepared [contributte/console](https://github.com/contributte/console)
integration.

```bash
composer require contributte/console
```

```neon
extensions:
	console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)
```

After that you can fire one of these commands.

| Command              | Info                            |
|----------------------|---------------------------------|
| executor:help        | Print cron syntax.              |
| executor:list        | List all jobs.                  |
| executor:run         | Run all due jobs.               |
| executor:force-run   | Force run selected executor job. |

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners) **contributte** development team.
Also thank you for using this package.
