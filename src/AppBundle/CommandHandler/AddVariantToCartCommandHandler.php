<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Command\AddVariantToCartCommandInterface;
use AppBundle\Factory\OrderItemFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AddVariantToCartCommandHandler implements AddVariantToCartCommandHandlerInterface
{
    /** @var OrderItemFactoryInterface */
    private $orderItemFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(OrderItemFactoryInterface $orderItemFactory, EntityManagerInterface $entityManager)
    {
        $this->orderItemFactory = $orderItemFactory;
        $this->entityManager = $entityManager;
    }

    public function handle(AddVariantToCartCommandInterface $addVariantToCartCommand): void
    {
        $cart = $addVariantToCartCommand->getCart();
        $variant = $addVariantToCartCommand->getVariant();
        $orderItem = $this->orderItemFactory->createFromVariant($variant);

        $cart->addItem($orderItem);

        $cart->getId() ?: $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
