<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required'
        ]);

        $request->user()->trips()->create([
            $request->only([
                'origin',
                'destination',
                'destination_name'
            ]),
        ]);

        return response()->json([
           'message' => 'ok'
        ]);
    }

    public function show(Request $request, Trip $trip)
    {
        // is the trip is associated with the authenticated user?

        if($trip->user->id === $request->user()->id) {
            return response()->json([
                'trip' => $trip
            ]);
        }

        if($trip->driver && $request->user()->driver){
            if($trip->driver->id === $request->user()->driver->id){
                return response()->json([
                    'trip' => $trip
                ]);
            }
        }

        return response()->json([
            'message' => 'can not find this trip'
        ], 404);
    }
}
