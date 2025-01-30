<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        // validate the phone number
        $request->validate([
            'phone' => 'required|numeric|min:11'
        ]);

        // find or create a user model
        $user = User::firstOrCreate([
           'phone' => $request->phone
        ]);

        if(!$user){
            return response()->json([
               'message' => 'Could not process a user with that phone number.'
            ], 401);
        }

        // send a user a one time use code
        $LoginCode = rand(111111,999999);

        // return back a response
    }
}
