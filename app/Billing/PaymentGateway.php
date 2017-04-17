<?php

namespace App\Billing;

interface PaymentGateway
{
    public function getValidTestToken();

    public function totalCharges();
}