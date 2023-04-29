<?php

namespace Panda\Tests\Api\Asset;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\Asset;
use Symfony\Component\HttpFoundation\Response;

final class GetAssetTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_an_asset_item()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'asset/get/item', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_an_asset_collection()
    {
        $fixtures = $this->loadFixturesFromFile('assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/assets', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'asset/get/collection', Response::HTTP_OK);
    }
}
