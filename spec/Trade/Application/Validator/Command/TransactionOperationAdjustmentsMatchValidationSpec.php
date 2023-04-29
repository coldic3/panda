<?php

namespace spec\Panda\Trade\Application\Validator\Command;

use Panda\Contract\AggregateRoot\Resource\ResourceInterface;
use Panda\Trade\Application\Command\Transaction\CreateTransactionCommand;
use Panda\Trade\Application\Validator\Command\TransactionOperationAdjustmentsMatch;
use Panda\Trade\Application\Validator\Command\TransactionOperationAdjustmentsMatchValidation;
use Panda\Trade\Domain\Model\OperationInterface;
use Panda\Trade\Domain\ValueObject\TransactionTypeEnum;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class TransactionOperationAdjustmentsMatchValidationSpec extends ObjectBehavior
{
    function let(ExecutionContextInterface $context)
    {
        $this->initialize($context);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TransactionOperationAdjustmentsMatchValidation::class);
        $this->shouldImplement(ConstraintValidator::class);
    }

    function it_supports_transaction_operation_adjustments_match_constraint_only(
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
            new TransactionOperationAdjustmentsMatch(),
        ]);
    }

    function it_does_nothing_if_both_from_and_to_operations_resources_match_adjustment_operations_resources(
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $toOperationResource,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $toOperation->getResource()->willReturn($toOperationResource);
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $firstAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);
        $firstAdjustmentOperationResource->compare($toOperationResource)->willReturn(true);
        $secondAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);
        $secondAdjustmentOperationResource->compare($toOperationResource)->willReturn(true);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }

    function it_does_nothing_if_only_one_operation_resource_does_not_match_at_least_one_adjustment_operation_resource(
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $toOperationResource,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $toOperation->getResource()->willReturn($toOperationResource);
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $firstAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);
        $firstAdjustmentOperationResource->compare($toOperationResource)->willReturn(false);
        $secondAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);
        $secondAdjustmentOperationResource->compare($toOperationResource)->willReturn(true);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }

    function it_does_nothing_if_both_from_and_to_operations_are_not_present(
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            null,
            null,
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }

    function it_adds_violation_if_both_from_and_to_operations_resources_do_not_match_at_least_one_adjustment_operation_resource(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        OperationInterface $fromOperation,
        OperationInterface $toOperation,
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $toOperationResource,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $toOperation->getResource()->willReturn($toOperationResource);
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $firstAdjustmentOperationResource->compare($fromOperationResource)->willReturn(false);
        $firstAdjustmentOperationResource->compare($toOperationResource)->willReturn(false);
        $secondAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);
        $secondAdjustmentOperationResource->compare($toOperationResource)->willReturn(true);

        $context->buildViolation(Argument::any())->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            $toOperation->getWrappedObject(),
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }

    function it_adds_violation_if_from_operation_resource_do_not_match_at_least_one_adjustment_operation_resource(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        OperationInterface $fromOperation,
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $fromOperationResource,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $fromOperation->getResource()->willReturn($fromOperationResource);
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $firstAdjustmentOperationResource->compare($fromOperationResource)->willReturn(false);
        $secondAdjustmentOperationResource->compare($fromOperationResource)->willReturn(true);

        $context->buildViolation(Argument::any())->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            $fromOperation->getWrappedObject(),
            null,
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }

    function it_adds_violation_if_to_operation_resource_do_not_match_at_least_one_adjustment_operation_resource(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        OperationInterface $toOperation,
        OperationInterface $firstAdjustmentOperation,
        OperationInterface $secondAdjustmentOperation,
        ResourceInterface $toOperationResource,
        ResourceInterface $firstAdjustmentOperationResource,
        ResourceInterface $secondAdjustmentOperationResource,
    ) {
        $toOperation->getResource()->willReturn($toOperationResource);
        $firstAdjustmentOperation->getResource()->willReturn($firstAdjustmentOperationResource);
        $secondAdjustmentOperation->getResource()->willReturn($secondAdjustmentOperationResource);

        $firstAdjustmentOperationResource->compare($toOperationResource)->willReturn(false);
        $secondAdjustmentOperationResource->compare($toOperationResource)->willReturn(true);

        $context->buildViolation(Argument::any())->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->validate(new CreateTransactionCommand(
            TransactionTypeEnum::ASK,
            null,
            $toOperation->getWrappedObject(),
            [
                $firstAdjustmentOperation->getWrappedObject(),
                $secondAdjustmentOperation->getWrappedObject(),
            ],
            new \DateTimeImmutable(),
        ), new TransactionOperationAdjustmentsMatch());
    }
}
