<?php

declare(strict_types=1);

namespace AppBundle\Command;

interface MadeToOrderProductCommandInterface
{
    public function getOptionValues(): array;

    public function setOptionValues(array $optionValues): void;
}
