<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoresNearPostcodeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, float $latitude, float $longitude, int $distance)
    {
        $stores = Store::closeTo($latitude, $longitude, $distance)->get();

        return response()->json($stores, 200);
    }
}
