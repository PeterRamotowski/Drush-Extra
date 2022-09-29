<?php

namespace Drupal\drush_extra\Helpers;

use Drush\Drush;
use Symfony\Component\Console\Application;

class CommandHelper
{
	/**
	 * @var Application
	 */
	protected $application;

	public function __construct()
	{
		$this->application = Drush::getApplication();
	}

	public function getCommandDescription(string $commandName): string
	{
		$command = $this->application->get($commandName);

		return $command->getDescription();
	}
}
