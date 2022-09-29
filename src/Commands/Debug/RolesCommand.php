<?php

namespace Drupal\drush_extra\Commands\Debug;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drush_extra\Helpers\CommandHelper;
use Drupal\drush_extra\Helpers\TableHelper;
use Drupal\user\RoleInterface;
use Drush\Commands\DrushCommands;

class RolesCommand extends DrushCommands
{
	use StringTranslationTrait;

	/**
	 * @var EntityTypeManagerInterface
	 */
	protected $entityTypeManager;

	/**
	 * @var CommandHelper
	 */
	protected $commandHelper;

	/**
	 * @var TableHelper
	 */
	protected $tableHelper;

	/**
	 * RolesCommand constructor.
	 *
	 * @param EntityTypeManagerInterface $entityTypeManager
	 * @param CommandHelper $commandHelper
	 * @param TableHelper $tableHelper
	 */
	public function __construct(
		EntityTypeManagerInterface $entityTypeManager,
		CommandHelper $commandHelper,
		TableHelper $tableHelper
	) {
		$this->entityTypeManager = $entityTypeManager;
		$this->commandHelper = $commandHelper;
		$this->outputTable = $tableHelper;
		parent::__construct();
	}

	/**
	 * Displays all roles with optional permissions
	 * 
	 * @param string $withPermissions
	 * 	Include permissions (optional)
	 * 
	 * @command debug:roles
	 * @aliases debr,dusr
	 * 
	 * @usage debug:roles
	 * @usage debug:roles permissions
	 */
	public function roles($withPermissions = null)
	{
		$commandDescription = $this->commandHelper->getCommandDescription(
			$this->commandData->annotationData()->get('command')
		);

		$this->io()->text($commandDescription);

		$roles = $this->entityTypeManager->getStorage('user_role')->loadMultiple();
		ksort($roles);

		$this->outputTable->addHeaderRow([$this->t('Role ID'), $this->t('Role label')]);

		if ($withPermissions) {
			$this->outputTable->addHeaderRowColumn($this->t('Permissions'));
		}

		/** @var RoleInterface $role */
		foreach ($roles as $roleId => $role) {
			$this->outputTable->addRow([$roleId, $role->label()], $roleId);

			if ($withPermissions) {
				$this->outputTable->addRowColumn(
					implode("\n", $role->getPermissions()),
					$roleId
				);
			}
		}

		$this->io()->table(
			$this->outputTable->getHeaderRows(),
			$this->outputTable->getRows()
		);
	}
}
