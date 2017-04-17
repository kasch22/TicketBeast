<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 11/04/2017
 * Time: 07:08
 */

namespace Tests\Feature;

use App\Billing\PaymentGateway;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Billing\FakePaymentGateway;

class PurchaseTicketsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var PaymentGateway
     */
    private $paymentGateway;


    protected function setUp()
    {
        parent::setUp();

        $this->paymentGateway = new FakePaymentGateway();
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);

    }

    private function orderTickets($concert, $params)
    {
        $this->json('POST', "/concerts/{$concert->id}/orders", $params);
    }

    private function assertValidationError($field)
    {
        $this->assertResponseStatus(422);
        $this->assertArrayHasKey($field, $this->decodeResponseJson());
    }

    public function test_user_can_purchase_concert_to_a_published_concert()
    {
        $concert = factory(Concert::class)->states('published')->create(['ticket_price' => 3000]);

        $this->orderTickets($concert, [
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);

        $this->assertResponseStatus(201);

        $this->assertEquals(9000, $this->paymentGateway->totalCharges());

        $order =  $concert->orders()->where('email', 'john@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals(3, $order->tickets()->count());
    }

    public function test_can_purchase_tickets_for_unpublished_concert()
    {
        $concert = factory(Concert::class)->states('unpublished')->create();

        $this->orderTickets($concert, [
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);

        $this->assertResponseStatus(404);
        $this->assertEquals(0, $concert->orders()->count());
        $this->assertEquals(0, $this->paymentGateway->totalCharges());


    }

    public function test_email_is_required_to_purchase_tickets()
    {
        $concert = factory(Concert::class)->states('published')->create();

        $this->orderTickets($concert, [
            'ticket_quantity' => 3,
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);


        $this->assertValidationError('email');
    }

    public function test_email_must_be_valid_to_purchase_tickets()
    {
        $concert = factory(Concert::class)->states('published')->create();

        $this->orderTickets($concert, [
            'email' => 'not-an-email-addres',
            'ticket_quantity' => 3,
            'payment_token' => $this->paymentGateway->getValidTestToken(),
        ]);

        $this->assertValidationError('email');
    }

     public function test_ticket_quantity_is_required_to_purchase_tickest()
     {
         $concert = factory(Concert::class)->states('published')->create();

         $this->orderTickets($concert, [
             'email' => 'test@email.com',
             'payment_token' => $this->paymentGateway->getValidTestToken(),
         ]);

         $this->assertValidationError('ticket_quantity');
     }

     public function test_ticket_quantity_must_be_more_than_zero_to_purchase_tickets()
     {
         $concert = factory(Concert::class)->states('published')->create();

         $this->orderTickets($concert, [
             'email' => 'test@email.com',
             'ticket_quantity' => 0,
             'payment_token' => $this->paymentGateway->getValidTestToken(),
         ]);

         $this->assertValidationError('ticket_quantity');
     }

     public function test_that_payment_token_is_required()
     {
         $concert = factory(Concert::class)->states('published')->create();

         $this->orderTickets($concert, [
             'email' => 'test@email.com',
             'ticket_quantity' => 2,
         ]);

         $this->assertValidationError('payment_token');
     }

     public function test_an_order_is_not_created_payment_fails()
     {
         $concert = factory(Concert::class)->states('published')->create();

         $this->orderTickets($concert, [
             'email' => 'test@email.com',
             'ticket_quantity' => 4,
             'payment_token' => 'invalid-payment-token',
         ]);

         $this->assertResponseStatus(422);
         $order =  $concert->orders()->where('email', 'john@example.com')->first();
         $this->assertNull($order);
     }
}