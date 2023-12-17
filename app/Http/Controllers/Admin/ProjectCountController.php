<?php

namespace App\Http\Controllers\Admin;


use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectCountController extends Controller
{
    public function __invoke(Request $request)
    {
        $boardCount = Board::count();
        return response()->json(['boardCount' => $boardCount]);
    }
}