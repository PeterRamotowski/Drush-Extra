<?php

namespace Drupal\drush_extra\Commands\Debug;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
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
	 * ImageStylesCommand constructor.
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
	 * Displays all images styles with effects
	 * 
	 * @command debug:image:styles
	 * @aliases debis
	 * 
	 * @usage debug:image:styles
	 */
	public function styles()
	{
		$imageStyles = $this->entityTypeManager->getStorage('image_style')->loadMultiple();
		$this->imageStylesList($imageStyles);
	}

	/**
	 * @param array $imageStyles
	 */
	protected function imageStylesList(array $imageStyles)
	{
		$tableHeader = [
			$this->t('Machine Name'),
			$this->t('Label'),
			$this->t('Effects')
		];

		$tableRows = [];

		/** @var ImageStyleInterface $style */
		foreach ($imageStyles as $styleId => $style) {
			$tableRows[] = [
				$styleId,
				$style->label(),
				''
			];

			$styleEffects = $this->styleEffectsList($style->getEffects());

			$tableRows = array_merge($tableRows, $styleEffects);
		}

		$this->io()->table($tableHeader, $tableRows);
	}

	/**
	 * @param ImageEffectPluginCollection $styleEffects
	 */
	protected function styleEffectsList(ImageEffectPluginCollection $styleEffects)
	{
		$tableRows = [];

		/** @var ImageEffectInterface $effect */
		foreach ($styleEffects as $effect) {
			$effectSummary = $effect->getSummary();

			$tableRows[] = [
				'',
				'',
				sprintf(
					"%s / %s",
					$effectSummary['#effect']['id'],
					$effect->label(),
				)
			];

			if (array_key_exists('#markup', $effectSummary)) {
				$markup = $effectSummary['#markup'];

				if (!empty($markup)) {
					$tableRows[] = [
						'',
						'',
						$markup
					];
				}
			}

			if (array_key_exists('#data', $effectSummary)) {
				$tableRows[] = [
					'',
					'',
					json_encode($effectSummary['#data'])
				];
			}

			$tableRows[] = ['', '', ''];
		}

		return $tableRows;
	}
}
