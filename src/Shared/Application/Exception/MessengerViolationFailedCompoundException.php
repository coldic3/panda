<?php

declare(strict_types=1);

namespace Panda\Shared\Application\Exception;

use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class MessengerViolationFailedCompoundException extends RuntimeException
{
    public function __construct(private readonly ValidationFailedException $validationFailedException)
    {
        parent::__construct($this->getCompoundMessage());
    }

    public function getCompoundMessage(): string
    {
        $messages = [];
        foreach ($this->validationFailedException->getViolations() as $violation) {
            $messages[] = $violation->getMessage();
        }

        return implode('\n', $messages);
    }
}
