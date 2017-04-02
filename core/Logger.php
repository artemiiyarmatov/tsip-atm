<?php

namespace core;

/**
 * Errors logger.
 */
class Logger
{
    /**
     * Get error message
     *
	 * @param string $errorMessage Error message
     *
     * @return void
	 */
	public static function CatchError(string $errorMessage)
	{
		Logger::PutErrorInLog("errors", $errorMessage);
		Logger::HaltProgram();
	}
	
    /**
     * Puts error in error log
     *
	 * @param string $file_name File name in base directory
	 * @param string $errorMessage Error message
     *
     * @return void
	 */
	private static function PutErrorInLog(string $file_name, string $errorMessage)
	{
		$errorMessage = date("d M Y H:i:s O") . ': ' . $errorMessage . PHP_EOL;
		
		$state = file_put_contents(BASE_PATH."{$file_name}.log", $errorMessage, FILE_APPEND | LOCK_EX);
		if ($state === false) {
            throw new Exception('Can\'t write to log file. Check your permissions to log file.');
		}
	}
	
    /**
     * Halt program immediately 
     *
     * @return void
     */
	private static function HaltProgram()
	{
		exit();
	}
}
?>

