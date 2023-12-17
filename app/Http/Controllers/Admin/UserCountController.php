<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCountController extends Controller
{
    public function __invoke(Request $request)
    {
        $userCount = User::count();
        return response()->json(['userCount' => $userCount]);
    }
}