<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

    /**
     * Get user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

