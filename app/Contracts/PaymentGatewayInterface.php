<?php
namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function create(array $data);
    public function verify(Request $request);

}
