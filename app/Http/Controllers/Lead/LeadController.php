<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
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
        ]);

    }

    public function showMember($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Lead/User', [
            'user' => $user,
        ]);
    }
}
