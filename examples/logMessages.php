<?php
/**
 * The SDK comes with a good Logger, which also can used from outside. The Logger has will be configured via
 * Configuration and can lock into a file or the screen.
 *
 * Use the Logger in the static way.
 */

use EpSDK\Configuration\Configuration;
use EpSDK\Utility\Logger\Logger;
use EpSDK\Utility\Logger\LogLevel;
use EpSDK\Utility\Logger\LogOutput;

/**
 * Configure the Logger.
 *
 * The Logger can be configured via JSON file or array.
 */
Configuration::addFromFile('dir/and/filename.json');   // configure via JSON
Configuration::set(
    [
        'Logger'   =>   [
            'level'     =>  LogLevel::ERROR,
            'output'    =>  LogOutput::SCREEN,
            'file'      =>  '/path/to/file.log' // optional if you want to lock to output file
        ]
    ]
);

/**
 * Log in code.
 *
 * There are three different log functions, which are controllable via Configuration.
 */
Logger::error('Some error message.');       // also prints the stack trace
Logger::warning('Some warning message.');
Logger::notify('Just a notification');

/**
 * Use Logger in development.
 *
 * There is a log function which every time prints (on screen or file). Use this to see variables on developing.
 */
Logger::force('This message will be printed.');
