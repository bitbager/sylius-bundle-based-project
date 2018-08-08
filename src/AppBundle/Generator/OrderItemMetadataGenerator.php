<?php

declare(strict_types=1);

namespace AppBundle\Generator;

use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Webmozart\Assert\Assert;

final class OrderItemMetadataGenerator implements OrderItemMetadataGeneratorInterface
{
    public function generateFromOptionValues(array $optionValues): string
    {
        $metadata = '';

        /** @var ProductOptionValueInterface $optionValue */
        foreach ($optionValues as $optionValue) {
            Assert::isInstanceOf($optionValue, ProductOptionValueInterface::class);

            $metadata .= $optionValue->getOptionCode() . ':' . $optionValue->getValue() . '&';
        }

        return rtrim($metadata, '&');
    }
}
