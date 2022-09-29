<?php

namespace Drupal\drush_extra\Commands\Debug;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drush_extra\Helpers\CommandHelper;
use Drupal\drush_extra\Helpers\TableHelper;
use Drupal\image\ImageEffectInterface;
use Drupal\image\ImageEffectPluginCollection;
use Drupal\image\ImageStyleInterface;
use Drush\Commands\DrushCommands;

class ImageStylesCommand extends DrushCommands
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
	 * ImageStylesCommand constructor.
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
	 * Displays all images styles with effects
	 * 
	 * @command debug:image:styles
	 * @aliases debis
	 * 
	 * @usage debug:image:styles
	 */
	public function styles()
	{
		$commandDescription = $this->commandHelper->getCommandDescription(
			$this->commandData->annotationData()->get('command')
		);

		$this->io()->text($commandDescription);

		$imageStyles = $this->entityTypeManager->getStorage('image_style')->loadMultiple();
		$this->imageStylesList($imageStyles);
	}

	/**
	 * @param array $imageStyles
	 */
	protected function imageStylesList(array $imageStyles)
	{
		$this->outputTable->addHeaderRow([
			$this->t('Machine Name'),
			$this->t('Label'),
			$this->t('Effects')
		]);

		/** @var ImageStyleInterface $style */
		foreach ($imageStyles as $styleId => $style) {
			$this->outputTable->addRow([
				$styleId,
				$style->label(),
				''
			]);

			$this->styleEffectsList($style->getEffects());
		}

		$this->io()->table(
			$this->outputTable->getHeaderRows(),
			$this->outputTable->getRows()
		);
	}

	/**
	 * @param ImageEffectPluginCollection $styleEffects
	 */
	protected function styleEffectsList(ImageEffectPluginCollection $styleEffects)
	{
		/** @var ImageEffectInterface $effect */
		foreach ($styleEffects as $effect) {
			$effectSummary = $effect->getSummary();

			$this->outputTable->addRowWithOnlyLastColumn(
				sprintf(
					"%s / %s",
					$effectSummary['#effect']['id'],
					$effect->label(),
				)
			);

			if (array_key_exists('#markup', $effectSummary)) {
				$markup = $effectSummary['#markup'];

				if (!empty($markup)) {
					$this->outputTable->addRowWithOnlyLastColumn($markup);
				}
			}

			if (array_key_exists('#data', $effectSummary)) {
				$this->outputTable->addRowWithOnlyLastColumn(
					json_encode($effectSummary['#data'])
				);
			}

			$this->outputTable->addEmptyRow();
		}
	}
}
