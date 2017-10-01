<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * This is the class for TaxClass attributes.
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.4.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class TaxClass
{
    /** @var string */
    protected $id = '';

    /** @var string */
    protected $name = '';

    /** @var int */
    protected $percentage = 0;

    /**
     * This is the constructor of the RaxClass object.
     *
     * @param   mixed[] $taxClassParameter  The taxClass parameter.
     * @since   0.4.0
     */
    public function __construct(array $taxClassParameter = null)
    {
        $taxClassParameter = $taxClassParameter ?? [];

        $this->id = $taxClassParameter['id'] ?? '';
        $this->name = $taxClassParameter['name'] ?? '';
        $this->percentage = $taxClassParameter['percentage'] ?? 0;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPercentage(): int
    {
        return $this->percentage;
    }

    /**
     * @param int $percentage
     */
    public function setPercentage(int $percentage)
    {
        $this->percentage = $percentage;
    }
}
