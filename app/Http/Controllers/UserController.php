<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

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
 


}
 
