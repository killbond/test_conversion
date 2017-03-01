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
        $first = Customer::orderBy('created_at')
            ->first();

        return response()->json([
            'start' => $first->created_at->timestamp,
            'end' => Carbon::now()->timestamp
        ]);
    }
}
