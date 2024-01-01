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
        $users = User::where('lead_id', $user->id)->orderBy('created_at', 'desc')->get();

        foreach($users as $lead) {
            $user['lead_id'] = $lead->id;
        }

        return Inertia::render('Lead/Members', [
            'users' => $users,
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
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            // Add other validation rules as needed
        ]);
 
        // Create a new user
        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'lead_id' => auth()->user()->id, // Use the authenticated lead's ID as the lead_id
            'password' => bcrypt($validatedData['password']), // You may want to set a default password or generate one
        ]);

        $user->save();

        return redirect()->route('lead.member.show', $user->id);
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

    public function status(Board $board)
    {
        // Update the board's archive status
        $updateData = [
            'archived' => $board->archived === 'Active' ? 'Done' : 'Active',
        ];

        if ($updateData['archived'] === 'Done') {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }
    
        // Update the board's status and timestamp
        $board->update($updateData);
 
        // Redirect to the 'boards.show' route with the board ID
        return redirect()->back();
    }
}
