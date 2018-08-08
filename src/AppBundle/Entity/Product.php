<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Sylius\Component\Product\Model\Product as BaseProduct;

class Product extends BaseProduct implements ProductInterface
{
    /** @var string */
    private $ean;

    /** @var string */
    private $type;

    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function setEan(?string $ean): void
    {
        $this->ean = $ean;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
