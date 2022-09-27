<?php

namespace Drupal\drush_extra\Commands\Node;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Commands\DrushCommands;
use Drupal\Core\State\StateInterface;

use function node_access_rebuild;

class AccessRebuildCommand extends DrushCommands
{
	use StringTranslationTrait;

	/**
	 * @var StateInterface
	 */
	protected $state;

	/**
	 * AccessRebuildCommand constructor.
	 *
	 * @param StateInterface $state
	 */
	public function __construct(
		StateInterface $state
	) {
		$this->state = $state;
		parent::__construct();
	}

	/**
	 * Rebuild node access permissions
	 * 
	 * @param string $batch
	 *   Process in batch mode (optional)
	 * 
	 * @command node:access:rebuild
	 * @aliases nar
	 * 
	 * @usage node:access:rebuild
	 * @usage node:access:rebuild batch
	 */
	public function rebuild($batch = null)
	{
		try {
			node_access_rebuild((bool) $batch);
		}
		catch (\Exception $e) {
			$this->io()->error($e->getMessage());
			return;
		}

		$needs_rebuild = $this->state->get('node.node_access_needs_rebuild') ?: false;
		if ($needs_rebuild) {
			$this->io()->error(
				$this->t('Rebuilding permissions was not successful')
			);
			return;
		}

		$this->io()->success(
			$this->t('Done rebuilding permissions')
		);
	}
}
