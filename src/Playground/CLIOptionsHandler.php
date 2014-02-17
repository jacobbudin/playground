<?php

namespace Playground;

/**
 * Processes available command line flags.
 */
class CLIOptionsHandler {
	/**
	 * Accept the REPL object and perform any setup necessary from the CLI flags.
	 *
	 * @param Playground $playground
	 * @param array $argv representation of $argv global
	 */
	public function handle($playground, $argv) {
		$args = getopt('hv', array('help', 'version'));

		foreach ($args as $option => $value) {
			switch ($option) {
				/*
				 * Show Usage info
				 */
				case 'h':
				case 'help':
					$this->_handleUsageInfo();
				break;

				/*
				 * Show version
				 */
				case 'v':
				case 'version':
					$this->_handleVersion();
				break;
			}
		}

		// Format, set packages
		$packages = array();
		foreach(array_slice($argv, 1) as $package){
			if(false !== ($i = strpos($package, ':'))){
				$package_name = substr($package, 0, $i);
				$package_version = substr($package, $i+1);
			}
			else{
				$package_name = $package;
				$package_version = '*';
			}
			$packages[$package_name] = $package_version;
		}
		$playground->setPackages($packages);
	}

	// -- Private Methods

	private function _handleUsageInfo() {
		echo <<<USAGE
Usage: playground [options]
playground is a tool for experimenting with Composer vendor libraries via the Boris REPL

Options:
	-h, --help      show this help message and exit
	-v, --version   show Playground version

USAGE;
		exit(0);
	}

	private function _handleVersion() {
		printf("Playground %s\n", Playground::VERSION);
		exit(0);
	}
}

