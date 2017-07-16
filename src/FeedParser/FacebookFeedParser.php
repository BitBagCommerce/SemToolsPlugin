<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\SEMToolsPlugin\FeedParser;

use Sylius\Component\Core\Model\ProductInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class FacebookFeedParser implements FeedParserInterface
{
    /**
     * @var FeedParserHelperInterface
     */
    private $feedParserHelper;

    /**
     * GoogleFeedParser constructor.
     *
     * @param FeedParserHelperInterface $feedParserHelper
     */
    public function __construct(FeedParserHelperInterface $feedParserHelper)
    {
        $this->feedParserHelper = $feedParserHelper;
    }

    /**
     * @inheritdoc
     */
    public function parse(ProductInterface $product)
    {
        return [
            'id' => $product->getId(),
            'availability' => $this->feedParserHelper->getAvailabilityStatus($product),
            'condition' => '',//TODO
            'description' => $product->getDescription(),
            'image_link' => $this->feedParserHelper->createLinkToImageProduct($product),
            'link' => $this->feedParserHelper->createLinkToProduct($product),
            'title' => $product->getName(),
            'price' => '',//TODO
            'mpn' => $product->getCode(),
        ];
    }
}