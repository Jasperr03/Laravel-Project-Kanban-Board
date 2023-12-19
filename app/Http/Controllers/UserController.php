<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{ 

    public function index()
    {
        // Fetch all users from the database
        $users = User::all();

        return Inertia::render('Admin/Users', [
            'users' => $users
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
 
