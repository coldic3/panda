<?php

namespace spec\Panda\Trade\Application\Validator\Command;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Panda\Trade\Application\Validator\Command\TransactionOperationsDiffer;
use Panda\Trade\Application\Validator\Command\TransactionOperationsDifferValidation;
use Panda\Trade\Domain\Model\Transaction\OperationInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class TransactionOperationsDifferValidationSpec extends ObjectBehavior
{
    function let(ExecutionContextInterface $context)
    {
        $this->initialize($context);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TransactionOperationsDifferValidation::class);
        $this->shouldImplement(ConstraintValidator::class);
    }

    function it_supports_transaction_operations_differ_constraint_only(
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        Constraint $constraint,
    ) {
        $this->shouldThrow(UnexpectedTypeException::class)->during('validate', [
            new CreateTransactionCommand(
                TransactionTypeEnum::ASK,
                $fromOperation->getWrappedObject(),
                $toOperation->getWrappedObject(),
                [],
                new \DateTimeImmutable(),
            ),
            $constraint,
        ]);
    }

    function it_supports_create_transaction_command_only()
    {
        $this->shouldThrow(UnexpectedTypeException::class)->during('validate', [
            new \stdClass(),
            new TransactionOperationsDiffer(),
        ]);
    }

    function it_does_nothing_if_from_operation_resource_is_null(
        OperationInterface $toOperation,
        ResourceInterface $toOperationResource,
    ) {
        $toOperation->getResource()->willReturn($toOperationResource);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            null,
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        ), new TransactionOperationsDiffer());
    }

    function it_does_nothing_if_to_operation_resource_is_null(
        OperationInterface $fromOperation,
        ResourceInterface $fromOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            null,
            [],
            new \DateTimeImmutable(),
        ), new TransactionOperationsDiffer());
    }

    function it_does_nothing_if_both_from_and_to_operations_resources_are_null()
    {
        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            null,
            null,
            [],
            new \DateTimeImmutable(),
        ), new TransactionOperationsDiffer());
    }

    function it_does_nothing_if_both_from_and_to_operations_resources_differ(
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $toOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $toOperation->getResource()->willReturn($toOperationResource);

        $fromOperationResource->compare($toOperationResource)->willReturn(false);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        ), new TransactionOperationsDiffer());
    }

    function it_adds_violation_if_both_from_and_to_operations_resources_are_equal(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $toOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $toOperation->getResource()->willReturn($toOperationResource);

        $fromOperationResource->compare($toOperationResource)->willReturn(true);

        $context->buildViolation(Argument::any())->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [],
            new \DateTimeImmutable(),
        ), new TransactionOperationsDiffer());
    }
}
