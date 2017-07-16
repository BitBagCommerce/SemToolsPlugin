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
interface FeedParserInterface
{
    const SIZE_VARIANT_PRODUCT = 'size';

    const COLOR_VARIANT_PRODUCT = 'color';

    /**
     * @param ProductInterface $product
     *
     * @return array
     */
    public function parse(ProductInterface $product);
}