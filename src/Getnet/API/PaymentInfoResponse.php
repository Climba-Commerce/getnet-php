<?php
namespace Getnet\API;

use Getnet\API\Exception\GetnetException;

/**
 * Resposta da consulta de pagamento (GET /v1/payments/info/{payment_id}).
 *
 * Lê só o primeiro nível do JSON. O mapperJson do BaseResponse é recursivo e deixaria
 * um campo de mesmo nome dentro de "payment" sobrescrever o status da transação.
 *
 * @package Getnet\API
 */
class PaymentInfoResponse
{
    private $payment_id;

    private $order_id;

    private $status;

    private $responseJson;

    /**
     * @param mixed $response resposta decodificada da API
     * @throws GetnetException quando a resposta não traz o status do pagamento
     */
    public function __construct($response)
    {
        if (!is_array($response) || empty($response['status']) || !is_string($response['status'])) {
            throw new GetnetException('Consulta de pagamento sem status na resposta');
        }

        $this->payment_id = $response['payment_id'] ?? null;
        $this->order_id = $response['order_id'] ?? null;
        $this->status = $response['status'];
        $this->responseJson = json_encode($response);
    }

    /**
     * @return string|null
     */
    public function getPaymentId()
    {
        return $this->payment_id;
    }

    /**
     * @return string|null
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getResponseJSON()
    {
        return $this->responseJson;
    }
}
