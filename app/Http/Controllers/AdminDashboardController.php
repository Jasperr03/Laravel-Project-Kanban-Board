<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::where('id','1')->count();
        $admins = Admin::where('id','1')->count();

        return view('admin.dashboard', compact('todo','inprogress','done','users','admins'));
    }
}
