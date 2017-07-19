<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\SEMToolsPlugin\FeedParser;

use BitBag\SEMToolsPlugin\FeedParser\FeedParserHelper;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Symfony\Component\Routing\Router;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
class FeedParserHelperSpec extends ObjectBehavior
{
    function let
    (
        Router $router,
        AvailabilityCheckerInterface $availabilityChecker,
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator,
        ChannelContextInterface  $channelContext,
        CurrencyContextInterface $currencyContext
    )
    {
        $this->beConstructedWith($router, $availabilityChecker, $productVariantPriceCalculator, $channelContext, $currencyContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FeedParserHelper::class);
    }
}
