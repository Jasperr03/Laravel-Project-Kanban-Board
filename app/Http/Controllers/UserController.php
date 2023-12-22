<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Admin;
use App\Models\Board;
use App\Models\CardList;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{ 

    public function index()
    {
        // Fetch all users from the database
        $users = User::orderBy('id', 'desc')->with('lead')->get();

        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    public function cardList($id) 
    {
        // Fetch all users from the database
        $cardList = CardList::where('board_id', $id)->get();

        return Inertia::render('Admin/Board', [
            'cardlist' => $cardList
        ]);
    }

    public function currentUser()
    {
        // Fetch all users from the database
        $user = Admin::findOrFail(1);

        return response()->json([
            'user' => $user,
        ]);
    }
 


}
 
