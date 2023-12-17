<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\CardList;
use Illuminate\Http\Request;

class CardListController extends Controller
{
    public function store(Board $board)
    {
        request()->validate([
            'name' => ['required']
        ]);
        CardList::create([
            'board_id' => $board->id,
            'user_id' => auth()->id(),
            'name' => request('name')
        ]);
        return redirect()->back();
    }
}
