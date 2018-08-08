<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Command\AddOptionsToCartCommandInterface;
use AppBundle\Factory\OrderItemFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Product\Repository\ProductVariantRepositoryInterface;

final class AddOptionsToCartCommandHandler implements AddOptionsToCartCommandHandlerInterface
{
    /** @var ProductVariantFactoryInterface */
    private $productVariantFactory;

    /** @var OrderItemFactoryInterface */
    private $orderItemFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ProductVariantRepositoryInterface */
    private $productVariantRepository;

    public function __construct(
        ProductVariantFactoryInterface $variantFactory,
        OrderItemFactoryInterface $orderItemFactory, 
        EntityManagerInterface $entityManager,
        ProductVariantRepositoryInterface $productVariantRepository
    )
    {
        $this->productVariantFactory = $variantFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->entityManager = $entityManager;
        $this->productVariantRepository = $productVariantRepository;
    }
    
    public function handle(AddOptionsToCartCommandInterface $addOptionsToCartCommand): void
    {
        $variant = $this->resolveVariant($addOptionsToCartCommand);
        $cart = $addOptionsToCartCommand->getCart();
        $orderItem = $this->orderItemFactory->createFromVariant($variant);

        $cart->addItem($orderItem);

        $cart->getId() ?: $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    private function resolveVariant(AddOptionsToCartCommandInterface $addOptionsToCartCommand): ProductVariantInterface
    {
        $product = $addOptionsToCartCommand->getProduct();
        $code = $product->getCode();

        foreach ($addOptionsToCartCommand->getOptionValues() as $optionValue) {
            $code .= '_' . $optionValue->getCode();
        }

        $variant = $this->productVariantRepository->findOneBy(['code' => $code]) ?? $this->productVariantFactory->createForProduct($product);

        foreach ($addOptionsToCartCommand->getOptionValues() as $optionValue) {
            $variant->addOptionValue($optionValue);
        }

        $variant->setCode($code);
        $variant->setPosition(1);

        $variant->getId() ?: $this->entityManager->persist($variant);

        return $variant;
    }
}
