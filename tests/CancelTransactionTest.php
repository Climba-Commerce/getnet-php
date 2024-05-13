<?php

namespace Tests;

use Getnet\API\Getnet;
use Getnet\API\Request;

class CancelTransactionTest extends TestBase
{
    public function testGetCancelRequestByClientKey()
    {
        $cancelKey = '12312321';
        $responseJson = $this->getAPIResponse();

        $mock = \Mockery::mock(Getnet::class);
        $mock->shouldAllowMockingProtectedMethods();

        $requestMock = \Mockery::mock(Request::class);
        $requestMock->shouldReceive("get")->with($mock, "/v1/payments/cancel/request/?cancel_custom_key=$cancelKey")->andReturn($responseJson);

        $mock->shouldReceive("generateRequest")->andReturn($requestMock);
        $mock->shouldReceive("getCancelRequestByClientKey")->passthru();
        $result = $mock->getCancelRequestByClientKey($cancelKey);
        $this->assertInstanceOf(\Getnet\API\BaseResponse::class, $result);
        $this->assertSame($responseJson, json_decode($result->getResponseJSON(), true));
    }

    public function testGetCancelRequestByClientKeyReturnErrorResponse()
    {
        $cancelKey = '12312321';

        $mock = \Mockery::mock(Getnet::class);
        $mock->shouldAllowMockingProtectedMethods();

        $requestMock = \Mockery::mock(Request::class);
        $requestMock->shouldReceive("get")
            ->with($mock, "/v1/payments/cancel/request/?cancel_custom_key=$cancelKey")
            ->andThrow(new \Exception(json_encode(['errorMessage' => 'Error Test'])));

        $mock->shouldReceive("generateRequest")->andReturn($requestMock);
        $mock->shouldReceive("getCancelRequestByClientKey")->passthru();
        $result = $mock->getCancelRequestByClientKey($cancelKey);
        $this->assertInstanceOf(\Getnet\API\BaseResponse::class, $result);
        $this->assertSame("ERROR", $result->getStatus());
        $this->assertSame('Error Test', json_decode($result->getResponseJSON(), true)['errorMessage']);
    }

    public function testGetCancelTransactionByRequestId()
    {
        $cancelRequestId = '12312321';
        $responseJson = $this->getAPIResponse();

        $mock = \Mockery::mock(Getnet::class);
        $mock->shouldAllowMockingProtectedMethods();

        $requestMock = \Mockery::mock(Request::class);
        $requestMock->shouldReceive("get")->with($mock, "/v1/payments/cancel/request/$cancelRequestId")->andReturn($responseJson);

        $mock->shouldReceive("generateRequest")->andReturn($requestMock);
        $mock->shouldReceive("getCancelTransactionByRequestId")->passthru();
        $result = $mock->getCancelTransactionByRequestId($cancelRequestId);
        $this->assertInstanceOf(\Getnet\API\BaseResponse::class, $result);
        $this->assertSame($responseJson, json_decode($result->getResponseJSON(), true));
    }

    public function testGetCancelTransactionByRequestIdReturnErrorResponse()
    {
        $cancelRequestId = '12312321';

        $mock = \Mockery::mock(Getnet::class);
        $mock->shouldAllowMockingProtectedMethods();

        $requestMock = \Mockery::mock(Request::class);
        $requestMock->shouldReceive("get")
            ->with($mock, "/v1/payments/cancel/request/$cancelRequestId")
            ->andThrow(new \Exception(json_encode(['errorMessage' => 'Error Test'])));

        $mock->shouldReceive("generateRequest")->andReturn($requestMock);
        $mock->shouldReceive("getCancelTransactionByRequestId")->passthru();
        $result = $mock->getCancelTransactionByRequestId($cancelRequestId);
        $this->assertInstanceOf(\Getnet\API\BaseResponse::class, $result);
        $this->assertSame("ERROR", $result->getStatus());
        $this->assertSame('Error Test', json_decode($result->getResponseJSON(), true)['errorMessage']);
    }

    private function getAPIResponse(): array
    {
        return [
            "seller_id" =>  "6eb2412c-165a-41cd-b1d9-76c575d70a28",
            "payment_id" =>  "06f256c8-1bbf-42bf-93b4-ce2041bfb87e",
            "cancel_request_at" =>  "2017-11-16T16:30:30Z",
            "cancel_request_id" =>  "20171117084237501",
            "cancel_custom_key" =>  "4ec33ee18f9e45bfb73c5c30667f9006",
            "terminal_nsu" =>  13978,
            "acquirer_transaction_id" =>  "113880914",
            "transaction_date" =>  "17052017",
            "currency" =>  "BRL",
            "transaction_type" =>  "FULL",
            "number_installments" =>  1,
            "amount" =>  19990,
            "cancel_amount" =>  19990,
            "status_processing_cancel_code" =>  "100",
            "status_processing_cancel_message" =>  "SOLICITACAO EM ANDAMENTO"
        ];
    }
}