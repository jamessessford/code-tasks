<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Http\Request;

class StoresThatDeliverToPostcodeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $postcode)
    {
        $postcode = Postcode::where('postcode', $postcode)->firstOrFail();

        $stores = Store::willDeliverTo($postcode->latitude, $postcode->longitude)->get();

        return response()->json($stores, 200);
    }
}
