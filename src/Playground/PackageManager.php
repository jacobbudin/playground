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
		
		// Construct composer.json configuration
		$composer_factory = new \Composer\Factory;
		$composer_file_contents = array('require' => array());
		foreach($this->_packages as $package){
			$composer_file_contents[$package] = '*';
		}
		$composer_file_path = '/Users/jacobbudin/Desktop/composer.json';
		file_put_contents($composer_file_path, json_encode($composer_file_contents, JSON_FORCE_OBJECT));

		// Build Composer, run installer
		$composer = $composer_factory->createComposer($io, $composer_file_path, true);
		
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

