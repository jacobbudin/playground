<?php

namespace Playground\IO;

class SimpleIO extends \Composer\IO\BaseIO
{
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
		var_dump($messages);
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
