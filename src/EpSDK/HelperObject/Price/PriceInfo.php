<?php
declare(strict_types=1);

namespace EpSDK\HelperObject\Price;

use EpSDK\HelperObject\Quantity;
use EpSDK\HelperObject\TaxClass;

/**
 * Class PriceInfo
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class PriceInfo
{
    /** @var Quantity */
    private $quantity;

    /** @var TaxClass */
    private $taxClass;

    /** @var Price */
    private $price;

    /** @var Price */
    private $depositPrice;

    /** @var Price */
    private $ecoParticipationPrice;

    /** @var Price */
    private $priceWithDeposits;

    /** @var Price */
    private $manufacturerPrice;

    /** @var Price */
    private $baseDeposits;

    /**
     * PriceInfo constructor.
     *
     * @param   array   $arrayObject
     * @since   0.4.0
     */
    public function __construct(array $arrayObject = null)
    {
        $arrayObject = $arrayObject ?? [];

        $this->quantity = new Quantity($arrayObject['quantity'] ?? null);
        $this->taxClass = new TaxClass($arrayObject['taxClass'] ?? null);
        $this->price = new Price($arrayObject['price'] ?? null);
        $this->depositPrice = new Price($arrayObject['depositPrice'] ?? null);
        $this->ecoParticipationPrice = new Price($arrayObject['ecoParticipationPrice'] ?? null);
        $this->priceWithDeposits = new Price($arrayObject['priceWithDeposits'] ?? null);
        $this->manufacturerPrice = new Price($arrayObject['manufacturerPrice'] ?? null);
        $this->baseDeposits = new Price($arrayObject['baseDeposits'] ?? null);
    }

    /**
     * @return Quantity
     */
    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return TaxClass
     */
    public function getTaxClass(): TaxClass
    {
        return $this->taxClass;
    }

    /**
     * @param TaxClass $taxClass
     */
    public function setTaxClass(TaxClass $taxClass)
    {
        $this->taxClass = $taxClass;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     */
    public function setPrice(Price $price)
    {
        $this->price = $price;
    }

    /**
     * @return Price
     */
    public function getDepositPrice(): Price
    {
        return $this->depositPrice;
    }

    /**
     * @param Price $depositPrice
     */
    public function setDepositPrice(Price $depositPrice)
    {
        $this->depositPrice = $depositPrice;
    }

    /**
     * @return Price
     */
    public function getEcoParticipationPrice(): Price
    {
        return $this->ecoParticipationPrice;
    }

    /**
     * @param Price $ecoParticipationPrice
     */
    public function setEcoParticipationPrice(Price $ecoParticipationPrice)
    {
        $this->ecoParticipationPrice = $ecoParticipationPrice;
    }

    /**
     * @return Price
     */
    public function getPriceWithDeposits(): Price
    {
        return $this->priceWithDeposits;
    }

    /**
     * @param Price $priceWithDeposits
     */
    public function setPriceWithDeposits(Price $priceWithDeposits)
    {
        $this->priceWithDeposits = $priceWithDeposits;
    }

    /**
     * @return Price
     */
    public function getManufacturerPrice(): Price
    {
        return $this->manufacturerPrice;
    }

    /**
     * @param Price $manufacturerPrice
     */
    public function setManufacturerPrice(Price $manufacturerPrice)
    {
        $this->manufacturerPrice = $manufacturerPrice;
    }

    /**
     * @return Price
     */
    public function getBaseDeposits(): Price
    {
        return $this->baseDeposits;
    }

    /**
     * @param Price $baseDeposits
     */
    public function setBaseDeposits(Price $baseDeposits)
    {
        $this->baseDeposits = $baseDeposits;
    }
}
