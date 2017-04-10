<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 04/04/2017
 * Time: 07:25
 */

namespace App\Http\Controllers;


use App\Concert;

class ConcertsController
{
    public function show($id)
    {
        $concert = Concert::whereNotNull('published_at')->findOrFail($id);

        return view('concerts.show', ['concert' => $concert]);
    }
}