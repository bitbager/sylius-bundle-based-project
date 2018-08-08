<?php

declare(strict_types=1);

namespace AppBundle\Factory;

use AppBundle\Entity\OrderItemInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface OrderItemFactoryInterface extends FactoryInterface
{
    public function createFromVariant(ProductVariantInterface $productVariant): OrderItemInterface;

    public function createFromOptionValues(array $optionValues): OrderItemInterface;
}
