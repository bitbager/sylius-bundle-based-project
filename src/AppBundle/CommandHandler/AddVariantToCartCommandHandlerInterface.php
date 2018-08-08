<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Command\AddVariantToCartCommandInterface;

interface AddVariantToCartCommandHandlerInterface
{
    public function handle(AddVariantToCartCommandInterface $addVariantToCartCommand): void;
}
