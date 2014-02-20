<?php

namespace Playground;

class Playground{
	const VERSION = '1.0.0';

	private $_packages = array();
	private $_packageManager;

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
		$this->_packageManager = $packageManager;

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

	/**
	 * Save composer.json and composer.lock files
	 *
	 * @param string $file_path Relative or absolute file path with or without .json extension
	 */
	public function saveComposer($composer_file_path) {
		$composer_ext = '.json';
		$composer_lock_ext = '.lock';

		// Add .json extension if missing
		if(strrpos($composer_file_path, '/') === (strlen($composer_file_path)-1)){
			$composer_file_path .= 'composer' . $composer_ext;
		}
		elseif(strrpos($composer_file_path, '.json') !== (strlen($composer_file_path) - strlen($composer_ext))){
			$composer_file_path .= $composer_ext;
		}

		// Auto-generate same-named .lock file
		$composer_file_lock_path = substr($composer_file_path, 0, 0 - strlen($composer_ext)) . $composer_lock_ext;

		// Remove Playground-specific settings, write files
		$composer_config = json_decode(file_get_contents($this->_packageManager->getComposerFilePath()), true);
		unset($composer_config['config']);

		if(version_compare(PHP_VERSION, '5.4.0', '>=')){
			$composer_config_json = json_encode($composer_config, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
		}
		else{
			$composer_config_json = json_encode($composer_config, JSON_FORCE_OBJECT);
		}

		if(!file_put_contents($composer_file_path, $composer_config_json)){
			throw new \Exception('Could not save composer.json-equivalent file');
		}
		if(!copy($this->_packageManager->getComposerLockFilePath(), $composer_file_lock_path)){
			throw new \Exception('Could not save composer.lock-equivalent file');
		}

		return true;
	}
}
