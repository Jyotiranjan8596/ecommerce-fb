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
        $userId = $user_profile->id;

        // Use conditional aggregation to get all counts in one query
        $userCounts = User::selectRaw('
        COUNT(*) as total,
        COUNT(CASE WHEN role = 3 THEN 1 END) as role_3,
        COUNT(CASE WHEN role = 4 THEN 1 END) as role_4
    ')->first();

        $count['users'] = $userCounts->total;
        $totaluser = $userCounts->role_3;
        $totalpos = $userCounts->role_4;

        $count['posts'] = 0;
        $count['posts_read'] = 0;
        $newPosts = 0;
        $topPosts = 0;

        return view('admin.index', compact('count', 'totaluser', 'totalpos', 'userId', 'user_profile', 'newPosts', 'topPosts'));
    }

    public function offer()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;

        return view('admin.offer.index', compact('userId', 'user_profile'));
    }
}
