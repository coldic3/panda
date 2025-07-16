<?php

declare(strict_types=1);

namespace Panda\Portfolio\Infrastructure\ApiSerializer;

use Panda\Portfolio\Infrastructure\ApiResource\QuantityRepresentation;
use Panda\Portfolio\Infrastructure\ApiResource\ResourceRepresentation;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InResourceRepresentationNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED_SUFFIX = 'IN_RESOURCE_REPRESENTATION_NORMALIZER_ALREADY_CALLED';

    public function normalize(mixed $data, ?string $format = null, array $context = []): \ArrayObject|array|string|int|float|bool|null
    {
        if (!is_object($data)) {
            throw new \InvalidArgumentException('Expected object, got '.gettype($data));
        }

        $context[get_class($data).self::ALREADY_CALLED_SUFFIX] = true;

        $normalized = $this->normalizer->normalize($data, $format, $context);

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
            $data instanceof ResourceRepresentation
            || $data instanceof QuantityRepresentation
        ;
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ResourceRepresentation::class => false,
            QuantityRepresentation::class => false,
        ];
    }
}
