<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class SliderController extends Controller
{
    public function range()
    {
        $range = Customer::range();
        return response()->json([
            'start' => $range['start']->timestamp,
            'end' => $range['end']->timestamp
        ]);
    }
}
