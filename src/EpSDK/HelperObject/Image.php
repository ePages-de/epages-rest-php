<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * This is the image class which is used for images.
 *
 * @package EpSDK\Connector
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.0.0
 */
class Image
{
    /** @var URL */
    private $url;

    /** @var string */
    private $classifier;

    /**
     * Image constructor.
     *
     * @param   array   $imageAttributes
     * @since   0.4.0
     */
    public function __construct(array $imageAttributes = null)
    {
        $imageAttributes = $imageAttributes ?? [];

        $this->url = new URL($imageAttributes['url'] ?? null);
        $this->classifier = $imageAttributes['classifier'] ?? '';
    }

    /**
     * @return URL
     */
    public function getUrl(): URL
    {
        return $this->url;
    }

    /**
     * @param URL $url
     */
    public function setUrl(URL $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getClassifier(): string
    {
        return $this->classifier;
    }

    /**
     * @param string $classifier
     */
    public function setClassifier(string $classifier)
    {
        $this->classifier = $classifier;
    }
}
