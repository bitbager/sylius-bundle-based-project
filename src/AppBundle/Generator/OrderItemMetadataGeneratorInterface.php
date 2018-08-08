<?php

declare(strict_types=1);

namespace AppBundle\Generator;

interface OrderItemMetadataGeneratorInterface
{
    public function generateFromOptionValues(array $optionValues): string;
}
