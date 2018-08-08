<?php

declare(strict_types=1);

namespace AppBundle\Factory;

use AppBundle\Entity\OrderItem;
use AppBundle\Entity\OrderItemInterface;
use AppBundle\Generator\OrderItemMetadataGeneratorInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

final class OrderItemFactory implements OrderItemFactoryInterface
{
    /** @var OrderItemMetadataGeneratorInterface */
    private $orderItemMetadataGenerator;

    public function __construct(OrderItemMetadataGeneratorInterface $orderItemMetadataGenerator)
    {
        $this->orderItemMetadataGenerator = $orderItemMetadataGenerator;
    }

    public function createNew(): OrderItemInterface
    {
        return new OrderItem();
    }

    public function createFromVariant(ProductVariantInterface $productVariant): OrderItemInterface
    {
        $orderItem = new OrderItem();

        $orderItem->setVariant($productVariant);
        $orderItem->setMetadata($this->orderItemMetadataGenerator->generateFromOptionValues($productVariant->getOptionValues()->toArray()));

        return $orderItem;
    }

    public function createFromOptionValues(array $optionValues): OrderItemInterface
    {
        $orderItem = new OrderItem();

        $orderItem->setMetadata($this->orderItemMetadataGenerator->generateFromOptionValues($optionValues));

        return $orderItem;
    }
}
