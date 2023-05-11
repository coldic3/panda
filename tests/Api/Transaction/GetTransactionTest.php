<?php

namespace Panda\Tests\Api\Transaction;

use Panda\Account\Domain\Model\User;
use Panda\Tests\Api\ApiTestCase;
use Panda\Tests\Util\HttpMethodEnum;
use Panda\Trade\Domain\Model\Transaction\Transaction;
use Symfony\Component\HttpFoundation\Response;

final class GetTransactionTest extends ApiTestCase
{
    /** @test */
    function it_requires_to_be_authorized()
    {
        $fixtures = $this->loadFixturesFromFile('transaction.yaml');

        /** @var Transaction $transaction */
        $transaction = $fixtures['transaction_ask'];

        $uri = sprintf('/transactions/%s', $transaction->getId());

        $this->request(HttpMethodEnum::GET, $uri);

        $this->assertUnauthorized($this->client->getResponse());
    }

    /** @test */
    function it_gets_a_transaction_item()
    {
        $fixtures = $this->loadFixturesFromFile('transaction.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];
        /** @var Transaction $transaction */
        $transaction = $fixtures['transaction_ask'];

        $uri = sprintf('/transactions/%s', $transaction->getId());

        $this->request(HttpMethodEnum::GET, $uri, [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/get/item_ask_type', Response::HTTP_OK);
    }

    /** @test */
    function it_gets_a_transaction_collection()
    {
        $fixtures = $this->loadFixturesFromFile('transactions.yaml');

        /** @var User $user */
        $user = $fixtures['user_panda'];

        $this->request(HttpMethodEnum::GET, '/transactions', [], $this->generateAuthorizationHeader($user));

        $this->assertResponse($this->client->getResponse(), 'transaction/get/collection_all_types', Response::HTTP_OK);
    }
}
