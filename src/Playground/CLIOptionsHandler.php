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

		$playground->setPackages(array_slice($argv, 1));
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

