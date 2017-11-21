<?php
declare(strict_types=1);

namespace EpSDK\Exception;

use Exception;

/**
 * Class ConfigurationIncompleteException
 *
 * @package EpSDK\Exception
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class ConfigurationIncompleteException extends Exception
{
    /**
     * Constructor.
     *
     * @param   string  $message
     * @since   0.4.0
     */
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
