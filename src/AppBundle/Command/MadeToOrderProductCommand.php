<?php

declare(strict_types=1);

namespace AppBundle\Command;

use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class MadeToOrderProductCommand implements MadeToOrderProductCommandInterface
{
    /** @var ProductOptionValueInterface[] */
    private $optionValues;

    public function getOptionValues(): array
    {
        return $this->optionValues;
    }

    public function setOptionValues(array $optionValues): void
    {
        $this->optionValues = $optionValues;
    }
}
