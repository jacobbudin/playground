<?php

namespace Playground;

/**
 * Downloads packages via Composer; injets them into Boris
 */
class PackageManager {
	private $_autoloadFilePath;
	private $_composerFilePath;
	private $_composerLockFilePath;
	private $_logFilePath;
	private $_playground;

	public function __construct($playground){
		date_default_timezone_set('UTC');
		$this->_playground = $playground;
	}

	public function getAutoloadFilePath(){
		return $this->_autoloadFilePath;
	}

	public function getComposerFilePath(){
		return $this->_composerFilePath;
	}

	public function getComposerLogFilePath(){
		return $this->_logFilePath;
	}

	public function getComposerLockFilePath(){
		return $this->_composerLockFilePath;
	}

	/**
	 * Download, update requested packages
	 */
	public function retrieve() {
		// Construct composer.json configuration
		$composer_vendor_path = tempnam(sys_get_temp_dir(), 'playground');
		if(!(unlink($composer_vendor_path) && mkdir($composer_vendor_path))){
			throw new \Exception('Cannot create temporary vendor directory');
		}

		// Create phony IO
		$this->_logFilePath = $composer_log_path = tempnam(sys_get_temp_dir(), 'playground');
		if(false === $composer_log_path){
			throw new \Exception('Cannot generate temporary composer.log file');
		}
		$composer_log = fopen($composer_log_path, 'w');
		if(false === $composer_log){
			throw new \Exception('Cannot open temporary composer.log file');
		}

		$io = new \Playground\IO\FileIO($composer_log);

		// Create Composer instance
		$composer_factory = new \Composer\Factory;
		$composer_file_contents = array(
			'require' => $this->_playground->getPackages(),
			'config' => array(
				'vendor-dir' => $composer_vendor_path,
			),
		);

		$this->_autoloadFilePath = $composer_vendor_path . DIRECTORY_SEPARATOR . 'autoload.php';

		$this->_composerFilePath = $composer_file_path = tempnam(sys_get_temp_dir(), 'playground');
		$this->_composerLockFilePath = $composer_file_path . '.lock';

		if(false === $composer_file_path){
			throw new \Exception('Cannot generate temporary composer.json file');
		}
		$composer_file = fopen($composer_file_path, 'w');
		if(false === $composer_file){
			throw new \Exception('Cannot open temporary composer.json file');
		}
		$composer_file_contents_json = json_encode($composer_file_contents, JSON_FORCE_OBJECT);
		if(false === $composer_file_contents_json){
			throw new \Exception('Cannot generate JSON from package list');
		}
		$composer_file_bytes_written = fwrite($composer_file, $composer_file_contents_json);
		if(false === $composer_file_bytes_written){
			throw new \Exception('Cannot write JSON from package list');
		}
		fclose($composer_file);

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

	public function cleanup(){
		$tmp_file_paths = array(
			$this->_autoloadFilePath,
			$this->_composerFilePath,
			$this->_logFilePath,
			$this->_composerLockFilePath,
		);

		foreach($tmp_file_paths as $tmp_file_path){
			if($tmp_file_path !== null){
				@unlink($tmp_file_path);
			}
		}
	}
}

