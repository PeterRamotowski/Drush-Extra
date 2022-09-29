<?php

namespace Drupal\drush_extra\Commands\Debug;

use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drush_extra\Helpers\CommandHelper;
use Drush\Commands\DrushCommands;

class EntityCommand extends DrushCommands
{
	use StringTranslationTrait;

	/**
	 * @var EntityTypeRepositoryInterface
	 */
	protected $entityTypeRepository;

	/**
	 * @var EntityTypeBundleInfoInterface
	 */
	protected $entityTypeBundle;

	/**
	 * @var CommandHelper
	 */
	protected $commandHelper;

	/**
	 * EntityCommand constructor.
	 *
	 * @param EntityTypeRepositoryInterface $entityTypeRepository
	 * @param EntityTypeBundleInfoInterface $entityTypeBundle
	 * @param CommandHelper $commandHelper
	 */
	public function __construct(
		EntityTypeRepositoryInterface $entityTypeRepository,
		CommandHelper $commandHelper,
	) {
		$this->entityTypeRepository = $entityTypeRepository;
		$this->entityTypeBundle = $entityTypeBundle;
		$this->commandHelper = $commandHelper;
		parent::__construct();
	}

	/**
	 * Displays all entities and bundles
	 *
	 * @param string $paramEntityGroup
	 * 	Entity group, e.g. Content (optional)
	 * 
	 * @command debug:entity
	 * @aliases debe
	 * 
	 * @usage debug:entity
	 * @usage debug:entity Content
	 */
	public function entity($paramEntityGroup = null)
	{
		$commandDescription = $this->commandHelper->getCommandDescription(
			$this->commandData->annotationData()->get('command')
		);

		$this->io()->text($commandDescription);
			$this->t('Entity class ID'),
			$this->t('Entity ID'),
			$this->t('Entity label'),
			$this->t('Bundle'),
			$this->t('Entity group')
		];
		$tableRows = [];

		$entityTypes = $this->entityTypeRepository->getEntityTypeLabels(true);

		if ($paramEntityGroup && !in_array($paramEntityGroup, array_keys($entityTypes))) {
			return;
		}

		if ($paramEntityGroup) {
			$entityGroups = [$paramEntityGroup];
		}
		else {
			$entityGroups = array_keys($entityTypes);
		}

		foreach ($entityGroups as $entityGroup) {
			$entities = $entityTypes[$entityGroup];

			foreach ($entities as $entityId => $entityType) {
				$tableRows[$entityId] = [
					$entityId,
					$entityId,
					$entityType->render(),
					'',
					$entityGroup
				];

				$entityBundles = $this->entityTypeBundle->getBundleInfo($entityId);

				foreach ($entityBundles as $bundleId => $bundle) {
					$tableRows[$bundleId] = [
						$entityId,
						$bundleId,
						$bundle['label'],
						'yes',
						$entityGroup
					];
				}
			}
		}

		$this->io()->table($tableHeader, array_values($tableRows));
	}
}
