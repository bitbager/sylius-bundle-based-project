<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Order\Model\OrderItemInterface as BaseOrderItemInterface;

interface OrderItemInterface extends BaseOrderItemInterface
{
    public function getVariant(): ?ProductVariantInterface;

    public function setVariant(?ProductVariantInterface $variant): void;

    public function setMetadata(string $metadata): void;

    public function getMetadata(): ?string;
}
