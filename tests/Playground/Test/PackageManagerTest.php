<?php

namespace Playground\Test;

use Playground\Playground;
use Playground\PackageManager;

class PackageManagerTest extends \PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider dataRetrieve
     */
    public function testRetrieve($expected, $packages)
    {
		$playground = new Playground;
		$playground->setPackages($packages);
        $package_manager = new PackageManager($playground);
		$package_manager->retrieve();

		// Check whether files created
		$composer_file = $package_manager->getComposerFilePath();
		$this->assertFileExists($composer_file);

		$composer_log_file = $package_manager->getComposerLogFilePath();
		$this->assertFileExists($composer_log_file);

		// Check if packages exist in composer.json
		$this->assertContains($packages,
			json_decode(file_get_contents($composer_file), true));

		// Check if Composer log file accurately includes feedback
		$composer_log_contents = file_get_contents($composer_log_file);
		foreach($expected as $output_snippet){
			$this->assertContains($output_snippet, $composer_log_contents);
		}

		$package_manager->cleanup();
	}

	public function dataRetrieve()
	{
		$data = array();

		$data['no packages'] = array(
			array('Nothing to install'),
			array(),
		);

		$data['just monolog'] = array(
			array('Installing monolog/monolog'),
			array('monolog/monolog' => '*'),
		);

		$data['monolog and expressive date'] = array(
			array(
				'Installing monolog/monolog',
				'jasonlewis/expressive-date',
			),
			array(
				'monolog/monolog' => '*',
				'jasonlewis/expressive-date' => '*',
			),
		);

		return $data;
	}

	public function testCleanup()
	{
		$playground = new Playground;
		$playground->setPackages(array());
        $package_manager = new PackageManager($playground);
		$package_manager->retrieve();

		$tmp_file_paths = array(
			$package_manager->getAutoloadFilePath(),
			$package_manager->getComposerFilePath(),
			$package_manager->getComposerLogFilePath(),
			$package_manager->getComposerLockFilePath(),
		);
		
		$package_manager->cleanup();

		// Check if files deleted
		foreach($tmp_file_paths as $tmp_file_path){
			$this->assertFalse(file_exists($tmp_file_path));
		}
	}
}


