<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Board;
use App\Models\Card;
use App\Models\CardList;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index() 
    {
        return Inertia::render('Boards/Index', [
            'boards' => auth()->user()->boards
        ]);
    }

    public function show(Board $board, Card $card = null) 
    {
        $board->load([
            'lists.cards' => fn($query) => $query->orderBy('position')
        ]);
        
        return Inertia::render('Boards/Show', [
            'board' => $board,
            'card' =>$card,
        ]);
    }
    
    public function update(Board $board) 
    {
        request()->validate([
            'name' => ['required', 'max:255']
        ]);

        $board->update(['name' => request('name')]);

        return redirect()->back();
    }

    public function store()
    {
        request()->validate([
            'name' => ['required']
        ]);

        $board = Board::create([
            'user_id' => auth()->id(),
            'name' => request('name')
        ]);

        $cardListNames = ['Todo', 'On going', 'Done'];

        foreach ($cardListNames as $listName) {
            CardList::create([
                'board_id' => $board->id,
                'user_id' => auth()->id(),
                'name' => $listName
            ]);
        }
        
        return redirect()->back();
    }
}
