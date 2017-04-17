<?php

namespace Tests\Unit;

use App\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConcertTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_get_formatted_date()
    {
        $concert = factory(Concert::class)->make([
            'date' => Carbon::parse('2012-06-01 00:00:00'),
        ]);

        $this->assertEquals('June, 1, 2012', $concert->getFormattedDate());
    }

    public function test_can_get_start_time()
    {
        $concert = factory(Concert::class)->make([
            'date' => Carbon::parse('2012-06-01 17:00:00'),
        ]);

        $this->assertEquals('5:00pm', $concert->getStartTime());
    }

    public function test_can_get_price_in_dollars()
    {
        $concert = factory(Concert::class)->make([
            'ticket_price' => 1900,
        ]);

        $this->assertEquals(19.00, $concert->getTicketPriceDollars());
    }

    public function test_concerts_with_published_date_are_returned()
    {
        $publishedConcertOne = factory(Concert::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $publishedConcertTwo = factory(Concert::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $unpublishedConcertOne = factory(Concert::class)->create(['published_at' => null]);

        $publishedConcerts = Concert::published()->get();

        $this->assertTrue($publishedConcerts->contains($publishedConcertOne));
        $this->assertTrue($publishedConcerts->contains($publishedConcertTwo));
        $this->assertFalse($publishedConcerts->contains($unpublishedConcertOne));
    }

    public function test_can_order_concerts_tickets()
    {
        $concert = factory(Concert::class)->create();

        $order = $concert->orderTickets('jane@example.com', 3);

        $this->assertEquals('jane@example.com', $order->email);
        $this->assertEquals(3, $order->tickets()->count());
    }
}