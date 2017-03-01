<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function data(Request $request)
    {
        $ranges = $request->input('ranges');

        if(!$ranges) {
            return response()->json([
                Customer::conversion()
            ]);
        }

        $data = [];
        for($index = 0; $index + 1 < count($ranges); $index++)
        {
            $data[] = Customer::conversion(
                Carbon::createFromTimestamp($ranges[$index]),
                Carbon::createFromTimestamp($ranges[$index + 1])
            );
        }

        return response()->json($data);
    }

    public function weeks()
    {
        return response()->json(Customer::weeks());
    }

    public function months()
    {
        return response()->json(Customer::months());
    }
}
