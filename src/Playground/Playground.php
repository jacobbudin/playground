<?php

namespace Playground;

class Playground{
	const VERSION = '1.0.0';

	private $_packages = array();

	/**
	 * Create a new Playground environment
	 */
	public function __construct(){
	}

	/**
	 * Get Composer packages to be installed (if necessary) and referenced
	 *
	 * @return array $packages Composer-style paths
	 */
	public function getPackages() {
		return $this->_packages;
	}


	/**
	 * Set Composer packages to be installed (if necessary) and referenced
	 *
	 * @param array $packages Composer-style paths
	 */
	public function setPackages($packages) {
		$this->_packages = $packages;
	}

	/**
	 * Start Boris
	 *
	 * This method never returns.
	 */

	public function start() {
		$packageManager = new \Playground\PackageManager($this->getPackages());
		$packageManager->retrieve();

		$boris = new \Boris\Boris();

		if($packageManager->getAutoloadFile()){
			require_once($packageManager->getAutoloadFile());
			$playground = $this;
			$boris->onStart(function($worker, $scope) use ($playground) {
				$worker->setLocal('playground', $playground);
			});
		}

		$boris->start();
	}
}
