<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $guarded = [];

    protected $dates = ['date', 'published_at'];

    /**
     * @return string
     */
    public function getFormattedDate()
    {
        return $this->date->format('F, j, Y');
    }

    /**
     * @return string
     *
     */
    public function getStartTime()
    {
        return $this->date->format('g:ia');
    }

    /**
     * @return float
     */
    public function getTicketPriceDollars()
    {
        return number_format($this->ticket_price / 100, 2);
    }

    public function scopePublished($query)
    {
        return Concert::whereNotNull('published_at');
    }


}
