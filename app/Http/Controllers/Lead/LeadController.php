<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
use App\Models\Board;
use App\Models\Card;
use App\Models\CardList;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index() {
        return Inertia::render('Lead/Dashboard');
    }

    public function members() {
        
        $user = auth()->user();

        // Assuming you have a "users" relationship in your Lead model
        $users = User::where('lead_id', $user->id)->get();

        return Inertia::render('Lead/Members', [
            'users' => $users,
            'current-user' => $user,
        ]);

    }

    public function showMember($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Lead/User', [
            'user' => $user,
        ]);
    }


    public function getBoardByUser($id)
    {
        $board = Board::where('user_id', $id)->get();

        return response()->json([
            'board' => $board,
        ]);
    }

    public function getCardListByBoard($board_id, $user_id)
    {
        $cardList = CardList::where(['board_id' => $board_id, 'user_id' => $user_id])->with('cards')->get();

        return response()->json([
            'card_list' => $cardList,
        ]);
    }

    public function getCardByCardList($board_id, $user_id, $card_list_id)
    {
        $card = Card::where(['board_id' => $board_id, 'user_id' => $user_id, 'card_list_id' => $card_list_id])->get();

        return response()->json([
            'card' => $card,
        ]);
    }

    public function createMember(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            // Add more validation rules as needed
        ]);

        // Create a new member
        $member = User::create($validatedData);

        $lead = auth()->user();
        
        $member->lead()->associate($lead)->save();

        return response()->json([
            'message' => 'Member added successfully', 
            'member' => $member]
        );
    }

    public function createBoard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $board = Board::create([
            'name' => $request->input('name'),
            'user_id' => $request->input('user_id'),
        ]);

        $cardListNames = ['Todo', 'On going', 'Done'];

        foreach ($cardListNames as $listName) {
            CardList::create([
                'board_id' => $board->id,
                'user_id' => $request->input('user_id'),
                'name' => $listName
            ]);
        }

        return response()->json(['board' => $board], 201);
    }
}
