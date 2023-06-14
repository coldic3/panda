<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Command\Portfolio;

use Panda\Core\Application\Command\CommandInterface;
use Symfony\Component\Uid\Uuid;

final readonly class ChangeDefaultPortfolioCommand implements CommandInterface
{
    public function __construct(public Uuid $id)
    {
    }
}
