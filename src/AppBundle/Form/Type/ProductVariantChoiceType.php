<?php

declare(strict_types=1);

namespace AppBundle\Form\Type;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductVariantChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $options['product']->getVariants();
                },
                'choice_value' => 'code',
                'choice_label' => function (ProductVariantInterface $variant) {
                    return $variant->getName();
                },
                'choice_translation_domain' => false,
                'multiple' => false,
                'expanded' => true,
            ])
            ->setRequired([
                'product',
            ])
            ->setAllowedTypes('product', ProductInterface::class)
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'app_product_variant_choice';
    }
}
