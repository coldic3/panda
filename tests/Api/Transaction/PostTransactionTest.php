<?php

namespace Panda\Tests\Api\Transaction;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Asset\AssetInterface;
use Symfony\Component\HttpFoundation\Response;

final class PostTransactionTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ]);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_creates_an_ask_transaction()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/post/valid_ask', Response::HTTP_CREATED);
    }

    /** @test */
    function it_creates_a_bid_transaction()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'bid',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/post/valid_bid', Response::HTTP_CREATED);
    }

    /** @test */
    function it_creates_a_deposit_transaction()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'deposit',
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 200,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/post/valid_deposit', Response::HTTP_CREATED);
    }

    /** @test */
    function it_creates_a_withdraw_transaction()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'withdraw',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 200,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/post/valid_withdraw', Response::HTTP_CREATED);
    }

    /** @test */
    function it_creates_a_fee_transaction()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'fee',
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 2,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/post/valid_fee', Response::HTTP_CREATED);
    }

    /** @test */
    function it_allows_many_adjustment_operations()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
                [
                    'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'transaction/post/valid_many_adjustment_operations',
            Response::HTTP_CREATED
        );
    }

    /** @test */
    function it_validates_for_mismatch_adjustments_operations()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];
        /** @var AssetInterface $thirdAsset */
        $thirdAsset = $fixtures['asset_3'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $thirdAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'transaction/post/invalid_operation_adjustments_match',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_same_operations()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => 2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => 1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'transaction/post/invalid_operations_differ',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /** @test */
    function it_validates_for_negative_quantity()
    {
        $fixtures = $this->loadFixturesFromFile('transaction_ready_assets.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var AssetInterface $firstAsset */
        $firstAsset = $fixtures['asset_1'];
        /** @var AssetInterface $secondAsset */
        $secondAsset = $fixtures['asset_2'];

        $this->request(HttpMethodEnum::POST, '/transactions', [
            'type' => 'ask',
            'fromOperation' => [
                'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                'quantity' => -19,
            ],
            'toOperation' => [
                'asset' => sprintf('/assets/%s', $secondAsset->getId()->toRfc4122()),
                'quantity' => -2,
            ],
            'adjustmentOperations' => [
                [
                    'asset' => sprintf('/assets/%s', $firstAsset->getId()->toRfc4122()),
                    'quantity' => -1,
                ],
            ],
            'concludedAt' => '2023-07-10T16:31:57.860Z',
        ], $this->generateAuthorizationHeader($user));

        $this->assertResponse(
            $this->client->getResponse(),
            'transaction/post/invalid_operation_quantity',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
