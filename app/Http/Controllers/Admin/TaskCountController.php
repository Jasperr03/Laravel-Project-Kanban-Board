<?php

namespace App\Http\Controllers\Admin;


use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskCountController extends Controller
{
    public function __invoke(Request $request)
    {
        $taskCount = Card::count();
        return response()->json(['taskCount' => $taskCount]);
    }

    public function index()
    {
        return Card::all();
    }
}