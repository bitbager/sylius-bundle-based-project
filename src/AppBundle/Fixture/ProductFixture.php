<?php

declare(strict_types=1);

namespace AppBundle\Fixture;

use AppBundle\Entity\ProductInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Generator\ProductVariantGeneratorInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Repository\ProductOptionRepositoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class ProductFixture extends AbstractFixture
{
    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var ProductFactoryInterface */
    private $productFactory;

    /** @var ProductVariantFactoryInterface */
    private $productVariantFactory;

    /** @var SlugGeneratorInterface */
    private $slugGenerator;

    /** @var ProductOptionRepositoryInterface */
    private $productOptionRepository;

    /** @var ProductVariantGeneratorInterface */
    private $productVariantGenerator;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        LocaleContextInterface $localeContext,
        ProductFactoryInterface $productFactory,
        ProductVariantFactoryInterface $productVariantFactory,
        SlugGeneratorInterface $slugGenerator,
        ProductOptionRepositoryInterface $productOptionRepository,
        ProductVariantGeneratorInterface $productVariantGenerator,
        EntityManagerInterface $entityManager
    )
    {
        $this->localeContext = $localeContext;
        $this->productFactory = $productFactory;
        $this->productVariantFactory = $productVariantFactory;
        $this->slugGenerator = $slugGenerator;
        $this->productOptionRepository = $productOptionRepository;
        $this->productVariantGenerator = $productVariantGenerator;
        $this->entityManager = $entityManager;
    }

    public function load(array $options): void
    {
        $localeCode = $this->localeContext->getLocaleCode();

        foreach ($options['custom'] as $customOption) {
            /** @var ProductInterface $product */
            $product = $this->productFactory->createNew();

            $product->setCode($customOption['code']);
            $product->setEan($customOption['ean']);
            $product->setType($customOption['type']);
            $product->setFallbackLocale($localeCode);
            $product->setCurrentLocale($localeCode);
            $product->setName($customOption['name']);
            $product->setSlug($this->slugGenerator->generate($customOption['name']));

            foreach ($customOption['options'] as $optionCode) {
                /** @var ProductOptionInterface $option */
                $option = $this->productOptionRepository->findOneBy(['code' => $optionCode]);

                $product->addOption($option);
            }

            if (ProductInterface::SIMPLE_TYPE === $product->getType()) {
                $this->productVariantGenerator->generate($product);
            }

            $this->entityManager->persist($product);
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
                            ->scalarNode('ean')->defaultNull()->end()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('type')->cannotBeEmpty()->end()
                            ->arrayNode('options')
                                ->prototype('scalar')
                            ->end()
        ;
    }

    public function getName(): string
    {
        return 'app_product';
    }
}
