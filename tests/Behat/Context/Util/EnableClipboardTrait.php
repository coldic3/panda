<?php

declare(strict_types=1);

namespace Panda\Tests\Behat\Context\Util;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Panda\Tests\Behat\Context\Hook\ClipboardContext;

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
