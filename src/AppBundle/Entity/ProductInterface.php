<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Sylius\Component\Product\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends BaseProductInterface
{
    const SIMPLE_TYPE = 'simple';
    const MADE_TO_ORDER_TYPE = 'made_to_order';

    public function getEan(): ?string;

    public function setEan(?string $ean): void;

    public function getType(): ?string;

    public function setType(string $type): void;
}
