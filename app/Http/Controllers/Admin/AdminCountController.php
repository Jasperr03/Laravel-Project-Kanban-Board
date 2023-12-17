<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCountController extends Controller
{
    public function __invoke(Request $request)
    {
        $adminCount = Admin::count();
        return response()->json(['adminCount' => $adminCount]);
    }
}