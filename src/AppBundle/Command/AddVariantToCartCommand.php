<?php

declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\ProductInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

final class AddVariantToCartCommand implements AddVariantToCartCommandInterface
{
    /** @var ProductInterface */
    private $product;

    /** @var ProductVariantInterface */
    private $variant;

    /** @var OrderInterface */
    private $cart;

    public function __construct(ProductInterface $product, OrderInterface $cart)
    {
        $this->product = $product;
        $this->cart = $cart;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function getCart(): OrderInterface
    {
        return $this->cart;
    }

    public function getVariant(): ?ProductVariantInterface
    {
        return $this->variant;
    }

    public function setVariant(?ProductVariantInterface $variant): void
    {
        $this->variant = $variant;
    }
}
