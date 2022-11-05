<?php

namespace Drupal\drush_extra\Helpers;

use Consolidation\AnnotatedCommand\CommandData;
use Drush\Drush;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class CommandHelper
{
	/**
	 * @var CommandData
	 */
	protected $commandData;

	/**
	 * @var Application
	 */
	protected $application;

	public function __construct(CommandData $commandData)
	{
		$this->commandData = $commandData;
		$this->application = Drush::getApplication();
	}

	public function getCommandDescription(): string
	{
		return $this->getCommand()->getDescription();
	}

	protected function getCommand(): Command
	{
		return $this->application->get($this->getCommandName());
	}

	protected function getCommandName(): string
	{
		return $this->commandData->annotationData()->get('command');
	}
}
