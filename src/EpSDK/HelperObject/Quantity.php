<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * This is the class for Quantity attributes.
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.0.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class Quantity
{
    /** @var int The quantity amount. */
    protected $amount = 0;

    /** @var string The quantity unit. */
    protected $unit = '';

    /**
     * This is the constructor of the Quantity object.
     *
     * @param   string[]    $quantityParameter  The quantity parameter.
     * @since   0.2.0
     */
    public function __construct(array $quantityParameter = null)
    {
        $quantityParameter = $quantityParameter ?? [];

        $this->amount = $quantityParameter['amount'] ?? 0;
        $this->unit = $quantityParameter['unit'] ?? '';
    }

    /**
     * Returns the quantity amount.
     *
     * @return  int  Gets the quantity amount.
     * @since   0.2.0
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Returns the quantity unit.
     *
     * @return  string  Gets the quantity unit.
     * @since   0.2.0
     */
    public function getUnit(): string
    {
        return $this->unit;
    }
}
