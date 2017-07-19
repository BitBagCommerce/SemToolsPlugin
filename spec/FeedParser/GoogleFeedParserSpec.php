<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\SEMToolsPlugin\FeedParser;

use BitBag\SEMToolsPlugin\FeedParser\FeedParserHelperInterface;
use BitBag\SEMToolsPlugin\FeedParser\FeedParserInterface;
use BitBag\SEMToolsPlugin\FeedParser\GoogleFeedParser;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class GoogleFeedParserSpec extends ObjectBehavior
{
    const CURRENCY_CODE_USD = 'USD';

    const SIZE_PRODUCT_XL = 'XL';

    const SIZE_CODE_VARIANT_PRODUCT = 'size';

    const ID_PRODUCT = 77;

    const SLUG_PRODUCT = 'mens-pique-polo-shirt';

    const NAME_PRODUCT = 'Mens Pique Polo Shirt';

    const DESCRIPTION_PRODUCT = 'Red, 100% cotton, large men’s t-shirt';

    const CODE_PRODUCT = 'GO12345OOGLE';

    const LINK_TO_PRODUCT = 'http://​www.example.​com/​asp​/sp.asp?cat=​12&id=1030';

    const LINK_TO_PRODUCT_IMAGE = 'http://​www.example.​com/​image1.​jpg';

    const AVAILABILITY_PRODUCT = 'in stock';

    const PRICE_PRODUCT = 15.00;

    const CONDITION_PRODUCT = 'new';

    const CLOTHES_CATEGORY_PRODUCT_GOOGLE_ID = 166;

    function let(FeedParserHelperInterface $feedParserHelper)
    {
        $this->beConstructedWith($feedParserHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GoogleFeedParser::class);
        $this->shouldHaveType(FeedParserInterface::class);
    }

    function it_parse_product
    (
        ProductInterface $product,
        ProductVariantInterface $productVariant,
        Collection $productVariants,
        Collection $productOptionValues,
        ProductOptionValueInterface $productOptionValue,
        FeedParserHelperInterface $feedParserHelper,
        TaxonInterface $taxon
    )
    {
        $feedParserHelper->getCurrencyCode()->willReturn(self::CURRENCY_CODE_USD);

        $productOptionValue->getName()->willReturn(self::SIZE_PRODUCT_XL);
        $productOptionValues->toArray()->willReturn([$productOptionValue]);
        $productVariant->getOptionValues()->willReturn($productOptionValues);

        $productVariant->getCode()->willReturn(self::SIZE_CODE_VARIANT_PRODUCT);
        $productVariants->toArray()->willReturn([$productVariant]);

        $product->getId()->willReturn(self::ID_PRODUCT);
        $product->getSlug()->willReturn(self::SLUG_PRODUCT);
        $product->getName()->willReturn(self::NAME_PRODUCT);
        $product->getDescription()->willReturn(self::DESCRIPTION_PRODUCT);
        $product->getCode()->willReturn(self::CODE_PRODUCT);
        $product->getVariants()->willReturn($productVariants);

        $taxon->getCode()->willReturn('clothes');
        $product->getMainTaxon()->willReturn($taxon);

        $feedParserHelper->createLinkToProduct($product)->willReturn(self::LINK_TO_PRODUCT);
        $feedParserHelper->createLinkToImageProduct($product)->willReturn(self::LINK_TO_PRODUCT_IMAGE);
        $feedParserHelper->getAvailabilityStatus($product)->willReturn(self::AVAILABILITY_PRODUCT);
        $feedParserHelper->getPriceProduct($product)->willReturn(self::PRICE_PRODUCT);

        $this->parse($product)->shouldReturn([
            'id' => self::ID_PRODUCT,
            'title' => self::NAME_PRODUCT,
            'description' => self::DESCRIPTION_PRODUCT,
            'link' => self::LINK_TO_PRODUCT,
            'image_link' => self::LINK_TO_PRODUCT_IMAGE,
            'availability' => self::AVAILABILITY_PRODUCT,
            'price' => self::PRICE_PRODUCT,
            'currency' => self::CURRENCY_CODE_USD,
            'google_​​product_​​category' => self::CLOTHES_CATEGORY_PRODUCT_GOOGLE_ID,
            'gtin' => '',
            'mpn' => self::CODE_PRODUCT,
            'condition' => 'new',
            'adult' => 'no',
            'age_​​group ' => '',
            'gender' => '',
            'material' => '',
            'pattern' => '',
            'size' => [self::SIZE_PRODUCT_XL],
        ]);
    }
}
