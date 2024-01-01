<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store()
    {
        request()->validate([
            'board_id' => ['required', 'exists:boards,id'],
            'card_list_id' => ['required', 'exists:card_lists,id'],
            'title' => ['required']
        ]);

        Card::create([
            'board_id' => request('board_id'),
            'card_list_id' => request('card_list_id'),
            'title' => request('title'),
            'user_id' => auth()->id()
        ]);

        return redirect()->back();

    }

    public function update(Card $card)
    {
        request()->validate([
            'title' => ['required']
        ]);

        $card->update([
            'title' => request('title'),
            'description' => request()->has('description') ? request('description') : $card->description
        ]);

        if(request()->has('redirectUrl')) {
            return redirect(request('redirectUrl'));
        }

        return redirect()->back();
    }

    public function move(Card $card) 
    {
        request()->validate([
            'cardListId' => ['required', 'exists:card_lists,id'],
            'position' => ['required', 'numeric']
        ]);

        $card->update([
            'card_list_id' => request('cardListId'), 
            'position' => round(request('position'), 5)
        ]);

        return redirect()->back();
    }

    public function status(Card $card)
    {
        // Update the card's status
        $updateData =[
            'status' => $card->status === 'Ongoing' ? 'Completed' : 'Ongoing',
        ];

        if ($updateData['status'] === 'Completed') {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }
    
        // Update the cards's status and timestamp
        $card->update($updateData);
 
        // Redirect to the 'boards.show' route with the board ID
        return redirect()->back();
    }


    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->route('boards.show', ['board' => $card->board_id]);
    }

}
