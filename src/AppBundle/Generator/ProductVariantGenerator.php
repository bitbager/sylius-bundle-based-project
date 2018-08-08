<?php

declare(strict_types=1);

namespace AppBundle\Generator;

use Sylius\Component\Product\Checker\ProductVariantsParityCheckerInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Generator\CartesianSetBuilder;
use Sylius\Component\Product\Generator\ProductVariantGeneratorInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

final class ProductVariantGenerator implements ProductVariantGeneratorInterface
{
    /** @var ProductVariantFactoryInterface */
    private $productVariantFactory;

    /** @var ProductVariantsParityCheckerInterface */
    private $variantsParityChecker;

    /** @var CartesianSetBuilder */
    private $setBuilder;

    public function __construct(
        ProductVariantFactoryInterface $productVariantFactory,
        ProductVariantsParityCheckerInterface $variantsParityChecker
    ) {
        $this->productVariantFactory = $productVariantFactory;
        $this->variantsParityChecker = $variantsParityChecker;
        $this->setBuilder = new CartesianSetBuilder();
    }

    public function generate(ProductInterface $product): void
    {
        Assert::true($product->hasOptions(), 'Cannot generate variants for an object without options.');

        $optionSet = [];
        $optionMap = [];

        foreach ($product->getOptions() as $key => $option) {
            foreach ($option->getValues() as $value) {
                $optionSet[$key][] = $value->getCode();
                $optionMap[$value->getCode()] = $value;
            }
        }

        $permutations = $this->setBuilder->build($optionSet);

        $position = 1;

        foreach ($permutations as $permutation) {
            $variant = $this->createVariant($product, $optionMap, $permutation, $position);

            if (!$this->variantsParityChecker->checkParity($variant, $product)) {
                $product->addVariant($variant);
            }

            $position++;
        }
    }

    private function createVariant(ProductInterface $product, array $optionMap, $permutation, int $position): ProductVariantInterface
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->productVariantFactory->createForProduct($product);

        $variant->setPosition($position);

        $this->addOptionValue($variant, $optionMap, $permutation);
        $this->addVariantCode($variant);

        return $variant;
    }

    private function addOptionValue(ProductVariantInterface $variant, array $optionMap, $permutation): void
    {
        if (!is_array($permutation)) {
            $variant->addOptionValue($optionMap[$permutation]);

            return;
        }

        foreach ($permutation as $code) {
            $variant->addOptionValue($optionMap[$code]);
        }
    }

    private function addVariantCode(ProductVariantInterface $variant): void
    {
        $code = $variant->getProduct()->getCode() . '_';

        foreach ($variant->getOptionValues() as $optionValue) {
            $code = $code . $optionValue->getCode() . '_';
        }

        $code = rtrim($code, '_');

        $variant->setCode($code);
    }
}
