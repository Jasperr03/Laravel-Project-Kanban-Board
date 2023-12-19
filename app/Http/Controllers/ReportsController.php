<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Board;
use App\Models\Card;
use App\Models\CardList;
use App\Models\User;

class ReportsController extends Controller
{
    public function index()
    {
        $board = Board::all(); 

        return Inertia::render('Admin/Reports');
    }
    
    public function users()
    {
        // Fetch all users from the database
        $users = User::all();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function cards()
    {
        // Fetch all cards from the database
        $cards = Card::all();

        return response()->json([
            'cards' => $cards,
        ]);
    }

    public function boards()
    {
        // Fetch all cards from the database
        $boards = Board::all();

        return response()->json([
            'boards' => $boards,
        ]);
    }
}
