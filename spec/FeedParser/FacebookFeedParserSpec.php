<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\SEMToolsPlugin\FeedParser;

use BitBag\SEMToolsPlugin\FeedParser\FacebookFeedParser;
use BitBag\SEMToolsPlugin\FeedParser\FeedParserHelperInterface;
use BitBag\SEMToolsPlugin\FeedParser\FeedParserInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class FacebookFeedParserSpec extends ObjectBehavior
{
    const CURRENCY_CODE_USD = 'USD';

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

    function it_is_initializable()
    {
        $this->shouldHaveType(FacebookFeedParser::class);
        $this->shouldHaveType(FeedParserInterface::class);
    }

    function let(FeedParserHelperInterface $feedParserHelper)
    {
        $this->beConstructedWith($feedParserHelper);
    }

    function it_parse_product
    (
        ProductInterface $product,
        FeedParserHelperInterface $feedParserHelper
    )
    {
        $feedParserHelper->getCurrencyCode()->willReturn(self::CURRENCY_CODE_USD);

        $product->getId()->willReturn(self::ID_PRODUCT);
        $product->getSlug()->willReturn(self::SLUG_PRODUCT);
        $product->getName()->willReturn(self::NAME_PRODUCT);
        $product->getDescription()->willReturn(self::DESCRIPTION_PRODUCT);
        $product->getCode()->willReturn(self::CODE_PRODUCT);

        $feedParserHelper->createLinkToProduct($product)->willReturn(self::LINK_TO_PRODUCT);
        $feedParserHelper->createLinkToImageProduct($product)->willReturn(self::LINK_TO_PRODUCT_IMAGE);
        $feedParserHelper->getAvailabilityStatus($product)->willReturn(self::AVAILABILITY_PRODUCT);
        $feedParserHelper->getPriceProduct($product)->willReturn(self::PRICE_PRODUCT);

        $this->parse($product)->shouldReturn([
            'id' => self::ID_PRODUCT,
            'availability' => self::AVAILABILITY_PRODUCT,
            'condition' => 'new',
            'description' => self::DESCRIPTION_PRODUCT,
            'image_link' => self::LINK_TO_PRODUCT_IMAGE,
            'link' => self::LINK_TO_PRODUCT,
            'title' => self::NAME_PRODUCT,
            'price' => self::PRICE_PRODUCT . ' ' . self::CURRENCY_CODE_USD,
            'mpn' => self::CODE_PRODUCT,
        ]);
    }
}
