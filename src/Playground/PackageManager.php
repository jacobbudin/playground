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
		/*
		$rootPackage = new \Composer\Package\RootPackage('Playground', '1', '1.0');
		$rootPackage->setRequires(array('monolog/monolog' => '1.0'));
		$composer->setPackage($rootPackage);
		 */
		$rootPackageLoader = new \Composer\Package\Loader\ArrayLoader;
		$rootPackage = $rootPackageLoader->load(array(
			'name' => '-',
			'version' => '1.0',
			'require' => array(
				'monolog/monolog' => '1.2.*',
			),
		), 'Composer\Package\RootPackage');
		$composer->setPackage($rootPackage);

		// Download Manager
		$downloadManager = new \Composer\Downloader\DownloadManager;
		$downloadManager->setOutputProgress(false);
		$downloadManager->setDownloader('git', new \Composer\Downloader\GitDownloader($io, $composer_config));
        $downloadManager->setDownloader('svn', new \Composer\Downloader\SvnDownloader($io, $composer_config));
		$composer->setDownloadManager($downloadManager);
		
		// Repository Manager
		$repositoryManager = new \Composer\Repository\RepositoryManager($io, $composer_config);
		$localRepositoryJson = new \Composer\Json\JsonFile('/Users/jacobbudin/Desktop/abc.json');
		$localRepository = new \Composer\Repository\InstalledFilesystemRepository($localRepositoryJson);

		$repositoryManager->setLocalRepository($localRepository);
		foreach($composer_config::$defaultRepositories as $defR){
			$repositoryManager->addRepository(new \Composer\Repository\ComposerRepository($defR, $io, $composer_config));
		}
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
		$libraryInstaller = new \Composer\Installer\LibraryInstaller($io, $composer);
		$installationManager->addInstaller($libraryInstaller);
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


