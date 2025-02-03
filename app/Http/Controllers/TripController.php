<?php

namespace App\Http\Controllers;

use App\Events\TripAccepted;
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

        $request->user()->trips()->create(
            $request->only([
                'origin',
                'destination',
                'destination_name'
            ])
        );

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

    public function accept(Request $request, Trip $trip)
    {
        // a driver accept a trip

        $request->validate([
           'driver_location' => 'required'
        ]);

        $trip->update([
           'driver_id' => $request->user()->id,
           'driver_location' => $request->driver_location,
        ]);

        $trip->load('driver.user');

        TripAccepted::dispatch($trip, $request->user->id);

        return response()->json([
            $trip
        ]);
    }

    public function start(Request $request, Trip $trip)
    {
        // a driver has started taking a passenger to their destination
        $trip->update([
            'is_started' => true
        ]);

        $trip->load('driver.user');

        return response()->json([
            $trip
        ]);
    }

    public function end(Request $request, Trip $trip)
    {
        // a driver has ended the trip
        $trip->update([
            'is_complete' => true
        ]);

        $trip->load('driver.user');

        return response()->json([
            $trip
        ]);
    }

    public function location(Request $request, Trip $trip)
    {
        // update the driver's current location

        $request->validate([
            'driver_location' => 'required'
        ]);

        $trip->update([
            'driver_location' => $request->driver_location
        ]);

        $trip->load('driver.user');

        return response()->json([
            $trip
        ]);
    }
}
