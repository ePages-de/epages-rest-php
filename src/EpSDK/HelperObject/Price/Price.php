<?php
declare(strict_types=1);

namespace EpSDK\HelperObject\Price;

/**
 * This is the class for Price objects.
 *
 * @package EpSDK\HelperObjectt\Price
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.0.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class Price
{
    /** @var float The amount of the price. */
    protected $amount = 0.0;

    /** @var string The currency of the price. */
    protected $currency = '';

    /** @var string The formatted price with currency. */
    protected $formatted = '';

    /** @var string The tax type of the price. */
    protected $taxType = '';

    /**
     * This is the constructor of the Price object.
     *
     * @param   mixed[] $priceParameter  The price parameter to create the Price object.
     * @since   0.0.0
     */
    public function __construct(array $priceParameter = null)
    {
        $priceParameter = $priceParameter ?? [];

        $this->amount = $priceParameter['amount'] ?? 0.0;
        $this->currency = $priceParameter['currency'] ?? '';
        $this->formatted = $priceParameter['formatted'] ?? '';
        $this->taxType = $priceParameter['taxType'] ?? '';
    }

    /**
     * Returns the amount.
     *
     * @return  float   Gets the amount.
     * @since   0.4.0
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Returns the currency.
     *
     * @return  string  Gets the currency.
     * @since   0.4.0
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Returns the formatted price with currency.
     *
     * @return  string  Returns the price with currency formatted.
     * @since   0.1.1
     */
    public function getFormatted(): string
    {
        return $this->formatted;
    }

    /**
     * Returns the tax type.
     *
     * @return  string  Gets the tax type.
     * @since   0.1.0
     */
    public function getTaxType(): string
    {
        return $this->taxType;
    }
}
