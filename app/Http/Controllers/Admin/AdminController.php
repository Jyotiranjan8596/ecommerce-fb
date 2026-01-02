<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId = $user_profile->id;

        // Initialize defaults to avoid undefined variable errors
        $count = ['users' => 0, 'posts' => 0, 'posts_read' => 0];
        $totaluser = 0;
        $totalpos = 0;

        // Prepare monthly data (Jan–Dec)
        $userChartData = array_fill(1, 12, 0);

        // Eloquent-based aggregation
        $results = User::query()
            ->selectRaw('
            COUNT(*) as total,
            SUM(role = 3) as role_3,
            SUM(role = 4) as role_4,
            MONTH(created_at) as month
        ')
            // ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at) WITH ROLLUP')
            ->get();

        foreach ($results as $row) {
            if (is_null($row->month)) {
                // ROLLUP totals
                $count['users'] = $row->total;
                $totaluser = $row->role_3;
                $totalpos = $row->role_4;
            } else {
                $userChartData[(int) $row->month] = $row->total;
            }
        }

        // Chart labels
        $userChartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $userChartData   = array_values($userChartData);

        // Static placeholders
        $newPosts = 0;
        $topPosts = 0;
        $posChartLabels = ['Jan', 'Feb', 'Mar'];
        $posChartData = [3, 7, 6];
        $salesChartLabels = ['Product A', 'Product B', 'Product C'];
        $salesChartData = [200, 450, 150];

        return view('admin.index', compact(
            'count',
            'totaluser',
            'totalpos',
            'userId',
            'user_profile',
            'newPosts',
            'topPosts',
            'userChartLabels',
            'userChartData',
            'posChartLabels',
            'posChartData',
            'salesChartLabels',
            'salesChartData'
        ));
    }



    public function offer()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;

        return view('admin.offer.index', compact('userId', 'user_profile'));
    }
}
