<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * Class HTML
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class HTML
{
    /** @var string */
    private $originalText;

    /**
     * HTML constructor.
     *
     * @param   string  $originalText
     * @since   0.4.0
     */
    public function __construct(string $originalText = null)
    {
        $this->originalText = $originalText ?? '';
    }

    /**
     * Return the HTMl string as original saved HTML stuff.
     *
     * @return  string
     * @since   0.4.0
     */
    public function __toString()
    {
        return $this->originalText;
    }

    /**
     * Get the HTML-stuff as plain text.
     *
     * @return  string
     * @since   0.4.0
     */
    public function asPlain(): string
    {
        return \strip_tags($this->originalText);
    }
}
