<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\SEMToolsPlugin\FeedParser;

use Sylius\Bundle\InventoryBundle\Templating\Helper\InventoryHelper;
use Sylius\Component\Channel\Context\RequestBased\ChannelContext;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Model\ProductInterface;
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
     * @var InventoryHelper
     */
    private $inventoryHelper;

    /**
     * @var ProductVariantPriceCalculator
     */
    private $productVariantPriceCalculator;

    /**
     * @var ChannelContext
     */
    private $channelContext;

    /**
     * FeedParserHelper constructor.
     *
     * @param Router $router
     * @param InventoryHelper $inventoryHelper
     * @param ProductVariantPriceCalculator $productVariantPriceCalculator
     * @param ChannelContext $channelContext
     */
    public function __construct(
        Router $router,
        InventoryHelper $inventoryHelper,
        ProductVariantPriceCalculator $productVariantPriceCalculator,
        ChannelContext $channelContext
    )
    {
        $this->router = $router;
        $this->inventoryHelper = $inventoryHelper;
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
        $this->channelContext = $channelContext;
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
        return $this->inventoryHelper->isStockAvailable($stockable);
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