<?php

declare(strict_types=1);

namespace AppBundle\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class ProductOptionFixture extends AbstractFixture
{
    /** @var FactoryInterface */
    private $productOptionFactory;

    /** @var FactoryInterface */
    private $productOptionValueFactory;

    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        FactoryInterface $productOptionFactory,
        FactoryInterface $productOptionValueFactory,
        LocaleContextInterface $localeContext,
        EntityManagerInterface $entityManager
    )
    {
        $this->productOptionFactory = $productOptionFactory;
        $this->productOptionValueFactory = $productOptionValueFactory;
        $this->localeContext = $localeContext;
        $this->entityManager = $entityManager;
    }

    public function load(array $options): void
    {
        $localeCode = $this->localeContext->getLocaleCode();
        $position = 1;

        foreach ($options['custom'] as $customOption) {
            /** @var ProductOptionInterface $productOption */
            $productOption = $this->productOptionFactory->createNew();

            $productOption->setCode($customOption['code']);
            $productOption->setFallbackLocale($localeCode);
            $productOption->setCurrentLocale($localeCode);
            $productOption->setName($customOption['name']);
            $productOption->setPosition($position);

            foreach ($customOption['values'] as $code => $value) {
                $value = $value ?? $code;
                /** @var ProductOptionValueInterface $productOptionValue */
                $productOptionValue = $this->productOptionValueFactory->createNew();
                $productOptionValue->setCode((string) $code);
                $productOptionValue->setCurrentLocale($localeCode);
                $productOptionValue->setFallbackLocale($localeCode);
                $productOptionValue->setValue((string) $value);

                $this->entityManager->persist($productOptionValue);
                $productOption->addValue($productOptionValue);
            }

            $this->entityManager->persist($productOption);

            $position++;
        }

        $this->entityManager->flush();
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('custom')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('code')->cannotBeEmpty()->end()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->arrayNode('values')
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('code')
                                ->prototype('scalar')
                            ->end()
        ;
    }

    public function getName(): string
    {
        return 'app_product_option';
    }
}
