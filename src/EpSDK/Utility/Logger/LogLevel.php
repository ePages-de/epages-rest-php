<?php
declare(strict_types=1);
namespace EpSDK\Utility\Logger;

/**
 * The Log Level 'enum'.
 *
 * Use this to define which log messages should be printed.
 *
 * @package EpSDK\Utility\Logger
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.0.1
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
abstract class LogLevel {
    /** @var String Use this to print all messages. **/
    const NOTIFICATION = 'NOTIFICATION';
    /** @var String Use this to print only warnings and errors. **/
    const WARNING = 'WARNING';
    /** @var String Use this to print only errors. **/
    const ERROR = 'ERROR';
    /** @var String Use this to print no log messages. **/
    const NONE = 'NONE';
    /** @var String This is only used for intern reasons. **/
    const FORCE = 'FORCE';
}
