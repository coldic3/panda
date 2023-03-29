<?php

namespace Panda\Tests\Api\Asset;

use Faker;
use Panda\Account\Domain\Model\User;
use Panda\Asset\Domain\Model\Asset;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Symfony\Component\HttpFoundation\Response;

final class PatchAssetTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => 'Acme Corporation',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_updates_an_asset()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => 'Acme Corporation',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'asset/patch/valid', Response::HTTP_OK);
    }

    /** @test */
    function it_validates_for_empty_data()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'name' => '',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/patch/invalid_empty_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /** @test */
    function it_validates_for_too_long_data()
    {
        $fixtures = $this->loadFixturesFromFile('asset.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Asset $asset */
        $asset = $fixtures['asset_acme'];

        $uri = sprintf('/assets/%s', $asset->getId());

        $faker = Faker\Factory::create();

        $this->request(HttpMethodEnum::PATCH, $uri, [
            'ticker' => $faker->lexify(str_repeat('?', 256)),
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'asset/patch/invalid_too_long_data',
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
