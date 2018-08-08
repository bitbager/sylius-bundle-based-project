<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\ProductInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

interface AddVariantToCartCommandInterface
{
    public function getProduct(): ProductInterface;

    public function getCart(): OrderInterface;

    public function getVariant(): ?ProductVariantInterface;

    public function setVariant(?ProductVariantInterface $variant): void;
}
