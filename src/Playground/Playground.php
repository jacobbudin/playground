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
		$packageManager = new \Playground\PackageManager($this);
		$packageManager->retrieve();

		$boris = new \Boris\Boris();

		if($autoload_file_path = $packageManager->getAutoloadFilePath()){
			require_once($autoload_file_path);
			$playground = $this;
			$boris->onStart(function($worker, $scope) use ($playground) {
				$worker->setLocal('playground', $playground);
			});
		}

		$boris->start();
	}
}
