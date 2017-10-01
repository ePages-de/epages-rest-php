<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * This is the class for Weight attributes.
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.4.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class Weight
{
    /** @var int The quantity amount. */
    protected $amount = 0;

    /** @var string The quantity unit. */
    protected $unit = '';

    /**
     * This is the constructor of the Weight object.
     *
     * @param   string[]    $weightParameter    The weight parameter.
     * @since   0.4.0
     */
    public function __construct(array $weightParameter = null)
    {
        $weightParameter = $weightParameter ?? [];

        $this->amount = $weightParameter['amount'] ?? 0;
        $this->unit = $weightParameter['unit'] ?? '';
    }

    /**
     * Returns the weight amount.
     *
     * @return  int  Gets the weight amount.
     * @since   0.4.0
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Returns the weight unit.
     *
     * @return  string  Gets the weight unit.
     * @since   0.4.0
     */
    public function getUnit(): string
    {
        return $this->unit;
    }
}
