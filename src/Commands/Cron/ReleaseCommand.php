<?php

namespace Drupal\drush_extra\Commands\Cron;

use Drupal\Core\Lock\LockBackendInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Commands\DrushCommands;
use Drush\Drush;
use Symfony\Component\Config\Definition\Exception\Exception;

class ReleaseCommand extends DrushCommands
{
	use StringTranslationTrait;

	/**
	 * @var LockBackendInterface
	 */
	protected $lock;

	/**
	 * ReleaseCommand constructor.
	 *
	 * @param LockBackendInterface $lock
	 */
	public function __construct(
		LockBackendInterface $lock
	) {
		$this->lock = $lock;
		parent::__construct();
	}

	/**
	 * Release cron system lock to run cron again
	 * 
	 * @command cron:release
	 * @aliases cror
	 * 
	 * @usage cron:release
	 */
	public function release()
	{
		try {
			$this->lock->release('cron');

			$this->io()->text($this->t('Cron lock was released successfully'));
		}
		catch (Exception $e) {
			$this->io()->error($e->getMessage());
		}

		Drush::drush(Drush::aliasManager()->getSelf(), 'cache:rebuild')->run();
	}
}
