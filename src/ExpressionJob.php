<?php declare(strict_types = 1);

namespace Contributte\Executor;

use Contributte\Scheduler\IJob as LegacyJob;
use Cron\CronExpression;
use DateTime;

abstract class ExpressionJob implements IJob, LegacyJob
{

	protected CronExpression $expression;

	public function __construct(string $cron)
	{
		$this->expression = new CronExpression($cron);
	}

	public function isDue(DateTime $dateTime): bool
	{
		return $this->expression->isDue($dateTime);
	}

	public function getExpression(): CronExpression
	{
		return $this->expression;
	}

}
