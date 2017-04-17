<?php

namespace App\Billing;


class FakePaymentGateway implements PaymentGateway
{
    private $charges;

    public function __construct()
    {
        $this->charges = collect([]);
    }


    public function getValidTestToken()
    {
        return 'test_token';
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }

    public function charge($amount, $token)
    {
        if ($token !== $this->getValidTestToken()) {
            throw new PaymentFailedException;
        }

        return $this->charges[] = $amount;
    }
}