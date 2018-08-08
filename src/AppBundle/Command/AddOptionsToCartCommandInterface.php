<?php

declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\ProductInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

interface AddOptionsToCartCommandInterface
{
    public function getProduct(): ProductInterface;

    public function getCart(): OrderInterface;

    public function addOptionValue(ProductOptionValueInterface $productOptionValue): void;

    /**
     * @return Collection|ProductOptionValueInterface[]
     */
    public function getOptionValues(): ?Collection;
}
