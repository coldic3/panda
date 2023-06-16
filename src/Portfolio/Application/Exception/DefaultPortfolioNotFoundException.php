<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\Exception;

final class DefaultPortfolioNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Default portfolio not found.');
    }
}
