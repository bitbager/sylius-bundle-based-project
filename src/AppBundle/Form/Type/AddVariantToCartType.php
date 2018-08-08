<?php

declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Command\AddVariantToCartCommandInterface;
use AppBundle\Entity\ProductInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddVariantToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('variant', ProductVariantChoiceType::class, ['product' => $options['product']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class',  AddVariantToCartCommandInterface::class)
            ->setDefault('csrf_protection',  false)
            ->setRequired('product')
            ->setAllowedTypes('product', ProductInterface::class)
        ;
    }
}
