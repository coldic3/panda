<?php

declare(strict_types=1);

namespace Panda\Portfolio\Application\OpenApi\Factory;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

final class PortfolioIdHeaderOpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /**
         * @var string $path
         * @var PathItem $methods
         */
        foreach ($openApi->getPaths()->getPaths() as $path => $methods) {
            $openApi->getPaths()->addPath($path, $methods->withParameters(array_merge(
                $methods->getParameters(),
                [new Parameter(
                    name: 'X-Portfolio-Id',
                    in: 'header',
                    description: 'Specify the portfolio you want to use. Will be used only if needed. If not specified, the default portfolio will be used.',
                    schema: ['type' => 'string', 'format' => 'uuid'],
                )]
            )));
        }

        return $openApi;
    }
}
