<?php

namespace Playground;

class Playground{
	const VERSION = '1.0.0';

	private $_packages = array();
	private $_boris;

	/**
	 * Create a new Playground environment
	 */
	public function __construct(){
		$this->_boris = new \Boris\Boris();
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
		$this->_boris->start();
	}
}
