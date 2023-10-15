<?php

declare(strict_types=1);

namespace Panda\Report\Infrastructure\OpenApi\Factory;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\OpenApi;
use Panda\Report\Application\ReportGenerator\AllocationReportGenerator;
use Panda\Report\Application\ReportGenerator\PerformanceReportGenerator;

final readonly class ReportVariousExamplesOpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    /** @param array<string, mixed> $context */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $paths = $openApi->getPaths();
        $path = $paths->getPath('/reports');

        if (null === $path) {
            return $openApi;
        }

        $post = $path->getPost();

        if (null === $post) {
            return $openApi;
        }

        $requestBody = $post->getRequestBody();

        if (null === $requestBody) {
            return $openApi;
        }

        $content = $requestBody->getContent();

        /** @var MediaType $mediaType */
        $mediaType = $content['application/ld+json'];

        $content['application/ld+json'] = $mediaType->withExamples(new \ArrayObject([
            PerformanceReportGenerator::TYPE => [
                'value' => [
                    'name' => 'string',
                    'entry' => (object) [
                        'type' => PerformanceReportGenerator::TYPE,
                        'configuration' => [
                            'fromDatetime' => '2020-01-01 00:00:00',
                            'toDatetime' => '2020-12-31 23:59:59',
                        ],
                    ],
                    'portfolio' => 'string',
                ],
            ],
            AllocationReportGenerator::TYPE => [
                'value' => [
                    'name' => 'string',
                    'entry' => (object) [
                        'type' => AllocationReportGenerator::TYPE,
                        'configuration' => [
                            'datetime' => '2020-01-01 00:00:00',
                        ],
                    ],
                    'portfolio' => 'string',
                ],
            ],
        ]));

        $path = $path->withPost(
            $post->withRequestBody(
                $requestBody->withContent($content)
            )
        );

        $paths->addPath('/reports', $path);

        return $openApi->withPaths($paths);
    }
}
