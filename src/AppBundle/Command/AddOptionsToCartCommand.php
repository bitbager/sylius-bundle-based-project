<?php

declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\ProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class AddOptionsToCartCommand implements AddOptionsToCartCommandInterface
{
    /** @var ProductInterface */
    private $product;

    /** @var OrderInterface */
    private $cart;

    /** @var Collection|ProductOptionValueInterface[] */
    private $optionValues;

    public function __construct(ProductInterface $product, OrderInterface $cart)
    {
        $this->product = $product;
        $this->cart = $cart;
        $this->optionValues = new ArrayCollection();
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function getCart(): OrderInterface
    {
        return $this->cart;
    }

    public function addOptionValue(ProductOptionValueInterface $productOptionValue): void
    {
        if (!$this->optionValues->contains($productOptionValue)) {
            $this->optionValues->add($productOptionValue);
        }
    }

    public function getOptionValues(): ?Collection
    {
        return $this->optionValues;
    }
}
