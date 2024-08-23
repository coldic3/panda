<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\ApiSerializer;

use Panda\Report\Infrastructure\ApiResource\ReportEntryRepresentation;
use Panda\Report\Infrastructure\ApiResource\ReportFileRepresentation;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InResourceRepresentationNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED_SUFFIX = 'IN_RESOURCE_REPRESENTATION_NORMALIZER_ALREADY_CALLED';

    /**
     * @param object $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): mixed
    {
        $context[get_class($object).self::ALREADY_CALLED_SUFFIX] = true;

        $normalized = $this->normalizer->normalize($object, $format, $context);

        if (!is_array($normalized)) {
            return $normalized;
        }

        unset($normalized['@id']);
        unset($normalized['@type']);

        return $normalized;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (is_object($data) && isset($context[get_class($data).self::ALREADY_CALLED_SUFFIX])) {
            return false;
        }

        return
            $data instanceof ReportEntryRepresentation
            || $data instanceof ReportFileRepresentation
        ;
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ReportEntryRepresentation::class => false,
            ReportFileRepresentation::class => false,
        ];
    }
}
