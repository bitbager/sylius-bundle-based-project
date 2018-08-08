<?php

declare(strict_types=1);

namespace AppBundle\Form\Type;

use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OptionValueChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    return $options['product_option']->getValues();
                },
                'choice_value' => 'code',
                'choice_label' => function (ProductOptionValueInterface $optionValue) {
                    return $optionValue->getName();
                },
                'multiple' => false,
                'expanded' => true,
            ])
            ->setRequired('product_option')
            ->setAllowedTypes('product_option', ProductOptionInterface::class)
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'app_product_option_choice';
    }
}
