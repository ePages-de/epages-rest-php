<?php
declare(strict_types=1);

namespace EpSDK\Utility\Logger;

/**
 * The Log Output 'enum'.
 *
 * Use this to define where the log messages should be printed.
 *
 * @package EpSDK\Utility\Logger
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.0.1
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
abstract class LogOutput {
    /** @var String Use this for print something on the screen. **/
    const SCREEN = 'SCREEN';
    /** @var String Use this for print something on the screen. **/
    const FILE = 'FILE';
}
