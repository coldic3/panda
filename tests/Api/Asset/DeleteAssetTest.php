<?php

namespace Panda\Tests\Api\Asset;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\Asset;
use Symfony\Component\HttpFoundation\Response;

final class DeleteAssetTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::DELETE, $uri, [
            'name' => 'Acme Corporation',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_deletes_an_asset()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::DELETE, $uri, [
            'name' => 'Acme Corporation',
        ], $this->generateAuthorizationHeader($user));

        $response = $this->client->getResponse();

        $this->assertResponseNoContent($response);
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
}
