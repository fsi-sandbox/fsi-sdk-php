<?php

require_once './tests/Mock/Sterling.php';

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use \InnovationSandbox\Sterling\Transfer;

class TransferTest extends TestCase
{

    private $mockHandler,
        $apiClient,
        $base_uri,
        $faker,
        $fixture;

    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker\Factory::create();
        $this->base_url = $this->faker->freeEmailDomain();
        $this->mockHandler = new MockHandler();
        $httpClient = new Client([
            'handler' => $this->mockHandler,
            'base_uri' => $this->base_uri
        ]);
        $this->apiClient = new Transfer($httpClient);
        $this->mock = new Sterling();
    }

    public function testShouldVerifyName()
    {
        $bvnData = $this->mock->nameEnquiryRequest();
        $this->mockHandler->append(new Response(
            200,
            [],
            json_encode(
                $this->mock->nameEnquiryReponse()
            )
        ));

        $result = json_decode($this->apiClient->InterbankNameEnquiry($bvnData));
        $this->assertObjectHasAttribute('message', $result);
        $this->assertObjectHasAttribute('data', $result);
        $this->assertEquals('OK', $result->message);
        $this->assertEquals('97', $result->data->data->status);
    }
}
