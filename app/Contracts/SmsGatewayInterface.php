<?php
namespace App\Contracts;

interface SmsGatewayInterface
{
    public function sendSms(string|array $to, string $message);
    public function getBalance();
    public function errorMessage();
}
