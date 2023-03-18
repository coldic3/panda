<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Util;

use App\Tests\Behat\Context\Hook\ClipboardContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

trait EnableClipboardTrait
{
    private ClipboardContext $clipboard;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->clipboard = $environment->getContext(ClipboardContext::class);
    }
}
