<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserInfo extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }
}
