<?php

declare(strict_types=1);

namespace AppBundle\Controller\Action;

use AppBundle\Command\AddOptionsToCartCommand;
use AppBundle\CommandHandler\AddOptionsToCartCommandHandlerInterface;
use AppBundle\Entity\ProductInterface;
use AppBundle\Form\Type\AddOptionsToCartType;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class AddOptionsToCartAction
{
    /** @var CartContextInterface */
    private $cartContext;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var AddOptionsToCartCommandHandlerInterface */
    private $addOptionsToCartCommandHandler;

    public function __construct(
        CartContextInterface $cartContext,
        ProductRepositoryInterface $productRepository,
        FormFactoryInterface $formFactory,
        AddOptionsToCartCommandHandlerInterface $addOptionsToCartCommandHandler
    )
    {
        $this->cartContext = $cartContext;
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
        $this->addOptionsToCartCommandHandler = $addOptionsToCartCommandHandler;
    }

    public function __invoke(Request $request): JsonResponse
    {
        Assert::eq(Request::METHOD_POST, $request->getMethod());

        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $request->get('productCode')]);

        Assert::isInstanceOf($product, ProductInterface::class);
        Assert::eq(ProductInterface::MADE_TO_ORDER_TYPE, $product->getType());

        $cart = $this->cartContext->getCart();
        $addOptionsToCartCommand = new AddOptionsToCartCommand($product, $cart);
        $form = $this->formFactory->create(
            AddOptionsToCartType::class,
            $addOptionsToCartCommand,
            ['product' => $product]
        );
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid()) {
            $this->addOptionsToCartCommandHandler->handle($addOptionsToCartCommand);

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
