<?php
declare(strict_types=1);
namespace EpSDK\Utility\Logger;

use EpSDK\Configuration\Configuration;

/**
 * This is a static object to log messages while executing.
 *
 * @package EpSDK\Utility\Logger
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.0.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class Logger
{
    /** @var LogLevel The log level describes which error should be logged. */
    private static $level = LogLevel::ERROR;

    /** @var LogOutput The output value is set to configure where logging message is made. */
    private static $output = LogOutput::SCREEN;

    /** @var string The default output file for printing log messages. */
    private static $outputFile;

    private static function reloadConfiguration()
    {
        $configuration = Configuration::getConfiguration('Logger');
        self::$level = $configuration['level'] ?? LogLevel::ERROR;
        self::$output = $configuration['output'] ?? LogOutput::SCREEN;
        self::$outputFile = $configuration['outputFile'] ?? null;
    }

    /**
     * This function prints errors.
     *
     * @param   string|array    $message    The message to print.
     * @since   0.0.0
     */
    public static function error($message)
    {
        self::reloadConfiguration();

        if (empty($message) || self::$level === LogLevel::NONE) {
            return;
        }

        self::printMessage($message, true);
    }

    /**
     * This function definitely prints the message.
     *
     * @param   string|array    $message    The message to print.
     * @since   0.0.0
     */
    public static function force($message)
    {
        if (empty($message)) {
            return;
        }

        self::printMessage($message);
    }

    /**
     * This function prints notifications.
     *
     * @param   string|array    $message    The message to print.
     * @since   0.0.0
     */
    public static function notify($message)
    {
        self::reloadConfiguration();

        if (empty($message) ||
            self::$level === LogLevel::ERROR ||
            self::$level === LogLevel::WARNING ||
            self::$level === LogLevel::NONE
        ) {
            return;
        }

        self::printMessage($message);
    }

    /**
     * This function prints warnings.
     *
     * @param   string|array    $message    The message to print.
     * @since   0.0.0
     */
    public static function warning($message)
    {
        self::reloadConfiguration();

        if (empty($message) ||
            self::$level === LogLevel::ERROR ||
            self::$level === LogLevel::NONE
        ) {
            return;
        }

        self::printMessage($message, true);
    }

    /**
     * This function returns the stacktrace.
     *
     * @return  string  The Stacktrace.
     * @since   0.1.2
     */
    private static function getStacktrace(): string
    {
        $stack = \debug_backtrace();
        $messageNumber = 0;
        $stacktrace = '';

        foreach ($stack as $stackEntry) {
            // don't show the first 3 messages, because this are intern Logger functions
            if ($messageNumber < 3) {
                $messageNumber++;
                continue;
            }

            $stacktrace .= 'function ' . $stackEntry['function'] . '(';
            $stacktrace .= \serialize($stackEntry['args']);
            $stacktrace .= ') called at ' . $stackEntry['file'] . ' line ' . $stackEntry['line'];
            $stacktrace .= "\n";
        }

        return $stacktrace;
    }

    /**
     * This function finally prints the message.
     *
     * @param   string|array    $message        The message to print.
     * @param   bool            $showStacktrace True if a stacktrace show be shown, false if not.
     * @since   0.0.0
     */
    private static function printMessage($message, $showStacktrace = false)
    {
        // print message, if it is array or string
        if (\is_array($message)) {
            $output = \print_r($message, true);
        } else {
            $output = "\n" . $message;
        }

        $preInformation = '[' . \date('d/M/Y:H:i:s O') . '] # ' . \md5($output);

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $preInformation .= ' from ' . $_SERVER['REMOTE_ADDR'];
        }

        $output = $preInformation . "\n" . $output;

        // print stacktrace if its needed
        if ($showStacktrace) {
            $output .= "\nStacktrace:\n" . self::getStacktrace();
        }

        switch (self::$output) {
            case LogOutput::SCREEN:
                echo '<pre>';
                echo $output;
                echo '</pre>';
                break;

            case LogOutput::FILE:
                \file_put_contents(self::$outputFile, $output . "\n===\n\n");
                break;
        }
    }
}
