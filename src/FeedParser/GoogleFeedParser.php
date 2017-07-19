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
    // list all categories products Google http://www.google.com/basepages/producttype/taxonomy-with-ids.pl-PL.xls
    const GOOGLE_PRODUCT_CATEGORY = [
        'clothes' => 166, // Ubrania i akcesoria
        'shirts' => '', // TODO
        't-shirts' => '', //TODO
        'pullover' => '', // TODO
        'trousers' => 204, // Ubrania i akcesoria > Ubrania > Spodnie
        'jeans' => 204, // Ubrania i akcesoria > Ubrania > Spodnie
        'shorts' => 207, // Ubrania i akcesoria > Ubrania > Szorty
        'shoes' => 187, //  Ubrania i akcesoria > Buty
        'accessories' => 167, // Ubrania i akcesoria > Akcesoria do ubrań
        'ties' => 176, // Ubrania i akcesoria > Akcesoria do ubrań > Krawaty i muszki
        'bow_ties' => 176, // Ubrania i akcesoria > Akcesoria do ubrań > Krawaty i muszki
        'pocket_sqaures' => '', //TODO
        'braces' => 179, // Ubrania i akcesoria > Akcesoria do ubrań > Szelki
        'underwear' => 213, // Ubrania i akcesoria > Ubrania > Bielizna i skarpety
    ];

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
            'price' => $this->feedParserHelper->getPriceProduct($product),
            'currency' => $this->feedParserHelper->getCurrencyCode(),
            'google_​​product_​​category' => $this->getGoogleCategoryProduct($product),
            'gtin' => '', //TODO
            'mpn' => $product->getCode(),
            'condition' => 'new', //TODO
            'adult' => 'no',
            'age_​​group ' => '', //TODO ---------
            'gender' => '', //TODO -------
            'material' => '', //TODO --------
            'pattern' => '', //TODO ------
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
            $result[] = $value->getName();
        }

        return $result;
    }

    /**
     * @param ProductInterface $product
     *
     * @return int|null
     */
    public function getGoogleCategoryProduct(ProductInterface $product)
    {
        $codeTaxon = $product->getMainTaxon()->getCode();

        return isset(self::GOOGLE_PRODUCT_CATEGORY[$codeTaxon]) ?
            self::GOOGLE_PRODUCT_CATEGORY[$codeTaxon] : null;
    }
}