<?php

declare(strict_types=1);

namespace AppBundle\Controller\Action;

use AppBundle\Command\AddVariantToCartCommand;
use AppBundle\CommandHandler\AddVariantToCartCommandHandlerInterface;
use AppBundle\Entity\ProductInterface;
use AppBundle\Form\Type\AddVariantToCartType;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class AddVariantToCartAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var AddVariantToCartCommandHandlerInterface */
    private $addVariantToCartCommandHandler;

    public function __construct(
        CartContextInterface $cartContext,
        ProductRepositoryInterface $productRepository,
        FormFactoryInterface $formFactory,
        AddVariantToCartCommandHandlerInterface $addVariantToCartCommandHandler
    )
    {
        $this->cartContext = $cartContext;
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
        $this->addVariantToCartCommandHandler = $addVariantToCartCommandHandler;
    }

    public function __invoke(Request $request): JsonResponse
    {
        Assert::eq(Request::METHOD_POST, $request->getMethod());

        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $request->get('productCode')]);

        Assert::isInstanceOf($product, ProductInterface::class);
        Assert::eq(ProductInterface::SIMPLE_TYPE, $product->getType());

        $cart = $this->cartContext->getCart();
        $addVariantToCartCommand = new AddVariantToCartCommand($product, $cart);
        $form = $this->formFactory->create(
            AddVariantToCartType::class,
            $addVariantToCartCommand,
            ['product' => $product]
        );
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid()) {
            $this->addVariantToCartCommandHandler->handle($addVariantToCartCommand);

            return new JsonResponse(
                ['cartId' => $cart->getId()],
                Response::HTTP_OK
            );
        }

        return new JsonResponse(
            ['message' => $form->getErrors()->__toString()],
            Response::HTTP_BAD_REQUEST
        );
    }
}
