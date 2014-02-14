<?php

namespace Playground;

/**
 * Processes available command line flags.
 */
class PackageManager {
	private $_autoloadFile;
	private $_packages = array();

	public function __construct($packages){
		$this->_packages = $packages;
	}

	/**
	 * Download, update requested packages
	 */
	public function retrieve() {
		$composer = new \Composer\Composer;
		$composer_config = new \Composer\Config;
		$composer->setConfig($composer_config);

		// Config
		/*
		$config_file = new \Composer\Json\JsonFile('/Users/jacobbudin/Desktop/abc.json');
		$config = new \Composer\Config\JsonConfigSource($config_file);
		foreach($this->_packages as $package){
			$config->addRepository($package, '*');
		}*/

		// IO
		$io = new \Composer\IO\NullIO;

		// Root Package
		$rootPackage = new \Composer\Package\RootPackage('Playground', '1', '1.0');
		$composer->setPackage($rootPackage);

		// Download Manager
		$downloadManager = new \Composer\Downloader\DownloadManager;
		$downloadManager->setOutputProgress(false);
		$composer->setDownloadManager($downloadManager);
		
		// Repository Manager
		$repositoryManager = new \Composer\Repository\RepositoryManager($io, $composer_config);
		$composer->setRepositoryManager($repositoryManager);
		
		// Event Dispatcher
		$eventDispatcher = new \Composer\EventDispatcher\EventDispatcher(
			$composer,
			$io
		);
		$composer->setEventDispatcher($eventDispatcher);

		// Autoload
		$autoloadGenerator = new \Composer\Autoload\AutoloadGenerator($eventDispatcher);
		$composer->setAutoloadGenerator($autoloadGenerator);

		// Installation Manager
		$installationManager = new \Composer\Installer\InstallationManager;
		$composer->setInstallationManager($installationManager);

		// Json
		$json = new \Composer\Json\JsonFile('/Users/jacobbudin/Desktop/abc.lock');

		// Locker
		$locker = new \Composer\Package\Locker(
			$io,
			$json,
			$repositoryManager,
			$installationManager,
			(string) time()
		);

		$this->_installer = new \Composer\Installer(
			$io,
			$composer_config,
			$rootPackage,
			$downloadManager,
			$repositoryManager,
			$locker,
			$installationManager,
			$eventDispatcher,
			$autoloadGenerator
		);

		$this->_installer->run();
	}
}


