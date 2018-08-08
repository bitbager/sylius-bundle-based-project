<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Command\AddOptionsToCartCommandInterface;

interface AddOptionsToCartCommandHandlerInterface
{
    public function handle(AddOptionsToCartCommandInterface $addVariantToCartCommand): void;
}
