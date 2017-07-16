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
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class GoogleFeedParser implements FeedParserInterface
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
        $data = [
            'id' => $product->getId(),
            'title' => $product->getName(),
            'description' => $product->getDescription(),
            'link' => $this->feedParserHelper->createLinkToProduct($product),
            'image_link' => $this->feedParserHelper->createLinkToImageProduct($product),
            'availability' => $this->feedParserHelper->getAvailabilityStatus($product),
            'price' => '', //TODO
            'currency' => '', //TODO
            'google_​​product_​​category' => '', //TODO
            'gtin' => '', //TODO
            'mpn' => $product->getCode(),
            'condition' => '', //TODO
            'adult' => 'no',
            'age_​​group ' => '', //TODO
            'gender' => '', //TODO
            'material' => '', //TODO
            'pattern' => '', //TODO
        ];

        $data = $this->parseVariantsProduct($product, $data);

        return $data;
    }

    /**
     * @param ProductInterface $product
     * @param array $data
     *
     * @return array
     */
    public function parseVariantsProduct(ProductInterface $product, array $data)
    {
        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants()->toArray() as $variant) {
            switch ($variant->getCode()) {
                case self::SIZE_VARIANT_PRODUCT:
                    $data[self::SIZE_VARIANT_PRODUCT] = $this->parseOptionValuesProduct($variant->getOptionValues()->toArray());
                    break;
                case self::COLOR_VARIANT_PRODUCT:
                    $data[self::COLOR_VARIANT_PRODUCT] = $this->parseOptionValuesProduct($variant->getOptionValues()->toArray());
                    break;
            }
        }

        return $data;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function parseOptionValuesProduct(array $values)
    {
        $result = [];

        /** @var ProductOptionValueInterface $value */
        foreach ($values as $value) {
            $values[] = $value->getName();
        }

        return $result;
    }
}