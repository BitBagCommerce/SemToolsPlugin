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
use Sylius\Component\Inventory\Model\StockableInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
interface FeedParserHelperInterface
{
    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    public function createLinkToProduct(ProductInterface $product);

    /**
     * @param StockableInterface $stockable
     *
     * @return bool
     */
    public function inventoryIsAvailable(StockableInterface $stockable);

    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    public function createLinkToImageProduct(ProductInterface $product);

    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    public function getAvailabilityStatus(ProductInterface $product);

    /**
     * @param ProductInterface $product
     *
     * @return int
     */
    public function getPriceProduct(ProductInterface $product);

    /**
     * @return string
     */
    public function getCurrencyCode();
}