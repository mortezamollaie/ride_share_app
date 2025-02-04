<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Medianasms\Errors\Error;
use Medianasms\Errors\HttpException;

class LoginController extends Controller
{
    /**
     * @throws HttpException
     * @throws Error
     */
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

        $user->login_code = $LoginCode;
        $user->save();

        // return back a response
        return response()->json([
            'message' => 'Text message notification sent.',
            'login_code' => $LoginCode
        ]);
    }

    public function verify(Request $request)
    {
        // validate incoming request
        $request->validate([
            'phone' => 'required|numeric|min:11',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        // find the user
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();

        // is the code provided the same one saved?

        // if so, return back an auth token

        if($user){
            $user->update([
                'login_code' => null
            ]);
            return response()->json([
               'token' => $user->createToken($request->login_code)->plainTextToken
            ]);
        }
        // if not, return back a message
        return response()->json([
            'message' => 'Invalid verification code.'
        ], 401);
    }
}
