<?php

require_once __DIR__ . "/../../src/RefactorExchangeRate/ExchangeRateService.php";

use PHPUnit\Framework\TestCase;
use App\Refactor\ExchangeRateService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ExchangeRateServiceTest extends TestCase
{
    public function testGetExchangeRate()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode([
            'rates'=> ['EUR' => 1.1],
        ])));

        $service = new ExchangeRateService("7VcJ3dntPvaPZ3frl4d6D38b9U4QLinS",$mockClient);
        //the result may be different because of dynamic data
        $this->assertEquals(1.1, $service->getExchangeRate('USD','EUR'));
    }
}