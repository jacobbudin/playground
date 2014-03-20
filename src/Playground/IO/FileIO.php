<?php

namespace Playground\IO;

/**
 * FileIO is a \Composer\IO-compatible class for non-interactive file logging
 */
class FileIO extends \Composer\IO\BaseIO
{
	/**
	 * @var Log file resource
	 */
	private $_logFile;

	/**
	 * Initiate log file resource
	 */
	public function __construct($log_file){
		$this->_logFile = $log_file;
	}

	/*
	 * Close log file resource
	 */
	public function __destruct(){
		fclose($this->_logFile);
	}

    /**
     * {@inheritDoc}
     */
    public function isInteractive()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isVeryVerbose()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isDecorated()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function write($messages, $newline = true)
    {
		fwrite($this->_logFile, strip_tags($messages));
    }

    /**
     * {@inheritDoc}
     */
    public function overwrite($messages, $newline = true, $size = 80)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function ask($question, $default = null)
    {
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function askConfirmation($question, $default = true)
    {
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function askAndValidate($question, $validator, $attempts = false, $default = null)
    {
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function askAndHideAnswer($question)
    {
        return null;
    }
}
