<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $count['users'] = User::count();


        $count['posts'] = 0;
        $count['posts_read'] = 0;
        $newPosts = 0;
        $topPosts = 0;
        $totaluser = User::where('role', 3)->count();
        $totalpos = User::where('role', 4)->count();
        return view('admin.index', compact('count', 'totaluser', 'totalpos', 'userId', 'user_profile', 'newPosts', 'topPosts'));
    }

    public function offer() {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;

        return view('admin.offer.index', compact('userId', 'user_profile'));
    }
}
