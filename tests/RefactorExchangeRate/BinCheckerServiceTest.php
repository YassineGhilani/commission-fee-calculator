<?php

require_once __DIR__ . '/../../src/RefactorExchangeRate/BinCheckerService.php';

use PHPUnit\Framework\TestCase;
use App\Refactor\BinCheckerService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class BinCheckerServiceTest extends TestCase
{
    public function testIsEu()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode([
            'Country' => ['A2' => 'FR']
        ])));

        $service = new BinCheckerService($mockClient);
        $this->assertTrue($service->isEu('123456'));
    }
}