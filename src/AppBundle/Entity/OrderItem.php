<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Sylius\Component\Order\Model\OrderItem as BaseOrderItem;
use Sylius\Component\Product\Model\ProductVariantInterface;

class OrderItem extends BaseOrderItem implements OrderItemInterface
{
    /** @var ProductVariantInterface */
    private $variant;

    /** @var string */
    private $metadata;

    public function getVariant(): ?ProductVariantInterface
    {
        return $this->variant;
    }

    public function setVariant(?ProductVariantInterface $variant): void
    {
        $this->variant = $variant;
    }

    public function setMetadata(string $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }
}
