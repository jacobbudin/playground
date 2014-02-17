<?php

namespace Playground;

/**
 * Processes available command line flags.
 */
class PackageManager {
	private $_autoloadFile;
	private $_packages = array();

	public function __construct($packages){
		date_default_timezone_set('UTC');
		$this->_packages = $packages;
	}

	/**
	 * Download, update requested packages
	 */
	public function retrieve() {
		// Create phony IO
		$io = new \Playground\IO\SimpleIO;
		
		// Build Composer, run installer
		$composer_factory = new \Composer\Factory;
		$composer = $composer_factory->createComposer($io, '/Users/jacobbudin/Desktop/composer.json', true);
		
		$this->_installer = new \Composer\Installer(
			$io,
			$composer->getConfig(),
			$composer->getPackage(),
			$composer->getDownloadManager(),
			$composer->getRepositoryManager(),
			$composer->getLocker(),
			$composer->getInstallationManager(),
			$composer->getEventDispatcher(),
			$composer->getAutoloadGenerator()
		);

		$this->_installer->run();
	}
}

