<?php

namespace Tests;

use Getnet\API\Exception\GetnetException;
use Getnet\API\Getnet;
use Getnet\API\PaymentInfoResponse;
use Getnet\API\Request;

class PaymentInfoTest extends TestBase
{
    private const PAYMENT_ID = '8719d0c4-2c3f-4360-abd9-8daf3834fe1f';

    private function getnetMock(string $paymentId, $response): Getnet
    {
        $mock = \Mockery::mock(Getnet::class);
        $mock->shouldAllowMockingProtectedMethods();

        $requestMock = \Mockery::mock(Request::class);
        $expectation = $requestMock->shouldReceive('get')->with($mock, "/v1/payments/info/$paymentId");
        if ($response instanceof \Exception) {
            $expectation->andThrow($response);
        } else {
            $expectation->andReturn($response);
        }

        $mock->shouldReceive('generateRequest')->andReturn($requestMock);
        $mock->shouldReceive('getPayment')->passthru();

        return $mock;
    }

    public function testGetPaymentReturnsPaymentData()
    {
        $response = ['payment_id' => self::PAYMENT_ID, 'order_id' => 'orderCode123', 'status' => 'CAPTURED'];

        $result = $this->getnetMock(self::PAYMENT_ID, $response)->getPayment(self::PAYMENT_ID);

        $this->assertInstanceOf(PaymentInfoResponse::class, $result);
        $this->assertSame(self::PAYMENT_ID, $result->getPaymentId());
        $this->assertSame('orderCode123', $result->getOrderId());
        $this->assertSame('CAPTURED', $result->getStatus());
        $this->assertSame($response, json_decode($result->getResponseJSON(), true));
    }

    public function testGetPaymentIgnoresNestedFieldsWithSameName()
    {
        $response = [
            'payment_id' => self::PAYMENT_ID,
            'order_id' => 'orderCode123',
            'status' => 'DENIED',
            'payment' => ['status' => 'APPROVED', 'order_id' => 'outroPedido'],
            'records' => [['status' => 'APPROVED']],
        ];

        $result = $this->getnetMock(self::PAYMENT_ID, $response)->getPayment(self::PAYMENT_ID);

        $this->assertSame('DENIED', $result->getStatus());
        $this->assertSame('orderCode123', $result->getOrderId());
    }

    public function testGetPaymentEncodesPaymentIdInPath()
    {
        $response = ['status' => 'APPROVED'];

        $result = $this->getnetMock('..%2Fcancel%2Frequest', $response)->getPayment('../cancel/request');

        $this->assertSame('APPROVED', $result->getStatus());
    }

    public function testGetPaymentDoesNotTurnFailureIntoErrorStatus()
    {
        $this->expectException(GetnetException::class);
        $this->expectExceptionMessage('Gateway Timeout');

        $this->getnetMock(self::PAYMENT_ID, new GetnetException('Gateway Timeout'))->getPayment(self::PAYMENT_ID);
    }

    /**
     * @dataProvider responsesWithoutStatus
     */
    public function testGetPaymentRejectsResponseWithoutStatus($response)
    {
        $this->expectException(GetnetException::class);

        $this->getnetMock(self::PAYMENT_ID, $response)->getPayment(self::PAYMENT_ID);
    }

    public function responsesWithoutStatus(): array
    {
        return [
            'nulo' => [null],
            'sem status' => [['payment_id' => self::PAYMENT_ID]],
            'status vazio' => [['status' => '']],
            'status nao textual' => [['status' => ['APPROVED']]],
        ];
    }
}
