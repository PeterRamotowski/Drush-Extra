<?php

namespace Drupal\drush_extra\Commands\Debug;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\RoleInterface;
use Drush\Commands\DrushCommands;

class RolesCommand extends DrushCommands
{
	/**
	 * @var EntityTypeManagerInterface
	 */
	protected $entityTypeManager;

	/**
	 * RolesCommand constructor.
	 *
	 * @param EntityTypeManagerInterface $entityTypeManager
	 */
	public function __construct(
		EntityTypeManagerInterface $entityTypeManager
	) {
		$this->entityTypeManager = $entityTypeManager;
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
		$roles = $this->entityTypeManager->getStorage('user_role')->loadMultiple();
		ksort($roles);

		$tableHeader = [
			'Role ID',
			'Role label'
		];

		if ($withPermissions) {
			$tableHeader[] = 'Permissions';
		}

		$tableRows = [];

		/** @var RoleInterface $role */
		foreach ($roles as $roleId => $role) {
			$tableRows[$roleId] = [
				$roleId,
				$role->label()
			];

			if ($withPermissions) {
				$tableRows[$roleId][] = implode("\n", $role->getPermissions());
			}
		}

		$this->io()->table($tableHeader, $tableRows);
	}
}