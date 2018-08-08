<?php

declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Command\AddOptionsToCartCommandInterface;
use AppBundle\Entity\ProductInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddOptionsToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ProductInterface $product */
        $product = $options['product'];

        foreach ($product->getOptions() as $productOption) {
            $builder->add($productOption->getCode(), OptionValueChoiceType::class, [
                'product_option' => $productOption,
                'multiple' => false,
                'label' => $productOption->getName(),
                'mapped' => false,
            ]);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            /** @var AddOptionsToCartCommandInterface $addOptionsToCartCommand */
            $addOptionsToCartCommand = $event->getForm()->getData();

            foreach ($event->getForm()->all() as $child) {
                $addOptionsToCartCommand->addOptionValue($child->getData());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class', AddOptionsToCartCommandInterface::class)
            ->setDefault('csrf_protection', false)
            ->setRequired('product')
            ->setAllowedTypes('product', ProductInterface::class)
        ;
    }
}
