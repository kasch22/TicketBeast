<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Concert;

class CanViewConcertTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_concert_listing_can_be_view_a_published_concert()
    {
        // Set up
        $concert = factory(Concert::class)->states('published')->create([
            'title' => 'Rock Show',
            'subtitle' => 'Its a rock show',
            'date' => Carbon::parse('2017-12-12, 00:00:00'),
            'ticket_price' => '3250',
            'venue' => 'The Cockpit',
            'venus_address' => '193 under bridge,',
            'city' => 'Leeds',
            'state' => 'North Yorkshire',
            'zip' => '12354',
            'additional_information' => 'Live show',
            'published_at' => Carbon::parse('-1 week'),
        ]);

        $this->visit('/concerts/' . $concert->id);

        $this->see($concert->title);
        $this->see($concert->title);
        $this->see($concert->date->format('F, j, Y'));
        $this->see($concert->ticket_price);
        $this->see($concert->venue);
        $this->see($concert->venus_address);
        $this->see($concert->city);
        $this->see($concert->state);
        $this->see($concert->zip);
        $this->see($concert->additional_information);
    }

    public function test_user_can_not_view_and_unpublished()
    {
        $concert = factory(Concert::class)->states('unpublished')->create();

        $this->get('/concerts/' . $concert->id);

        $this->assertResponseStatus(404);
    }
}
