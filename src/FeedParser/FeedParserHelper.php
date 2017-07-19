<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\SEMToolsPlugin\FeedParser;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\RequestBased\ChannelContext;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Sylius\Component\Inventory\Model\StockableInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class FeedParserHelper implements FeedParserHelperInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var AvailabilityCheckerInterface
     */
    private $availabilityChecker;

    /**
     * @var ProductVariantPriceCalculator
     */
    private $productVariantPriceCalculator;

    /**
     * @var ChannelContext
     */
    private $channelContext;

    /**
     * @var CurrencyContextInterface
     */
    private $currencyContext;

    /**
     * FeedParserHelper constructor.
     *
     * @param Router $router
     * @param AvailabilityCheckerInterface $availabilityChecker
     * @param ProductVariantPriceCalculatorInterface $productVariantPriceCalculator
     * @param ChannelContextInterface $channelContext
     * @param CurrencyContextInterface $currencyContext
     */
    public function __construct(
        Router $router,
        AvailabilityCheckerInterface $availabilityChecker,
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator,
        ChannelContextInterface  $channelContext,
        CurrencyContextInterface $currencyContext
    )
    {
        $this->router = $router;
        $this->availabilityChecker = $availabilityChecker;
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
        $this->channelContext = $channelContext;
        $this->currencyContext = $currencyContext;
    }

    /**
     * @inheritdoc
     */
    public function createLinkToProduct(ProductInterface $product)
    {
        return $this->router->generate('sylius_shop_product_show', [
            'slug' => $product->getSlug()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @inheritdoc
     */
    public function createLinkToImageProduct(ProductInterface $product)
    {
        return $this->router->generate('liip_imagine_filter', [
            'path' => $product->getImages()->first()->getPath(),
            'filter' => 'sylius_shop_product_original',
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @inheritdoc
     */
    public function inventoryIsAvailable(StockableInterface $stockable)
    {
        return $this->availabilityChecker->isStockAvailable($stockable);
    }

    /**
     * @inheritdoc
     */
    public function getPriceProduct(ProductInterface $product)
    {
        $price = $this->productVariantPriceCalculator->calculate($product->getVariants()->first(), [
                'channel' => $this->channelContext->getChannel()
            ]
        );

        return $price / 100;
    }

    /**
     * @inheritdoc
     */
    public function getCurrencyCode()
    {
        return $this->currencyContext->getCurrencyCode();
    }

    /**
     * @inheritdoc
     */
    public function getAvailabilityStatus(ProductInterface $product)
    {
        return $this->inventoryIsAvailable($product->getVariants()->first()) ?
            'in stock' : 'out of stock';
    }
}