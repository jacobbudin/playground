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
		$packageManager = new \Playground\PackageManager($this->_packages);
		$packageManager->retrieve();

		$boris = new \Boris\Boris();
		$boris->start();
	}
}
