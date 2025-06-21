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

        // Combined query using conditional aggregation and grouping by month
        $results = DB::select("
        SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN role = 3 THEN 1 END) as role_3,
            COUNT(CASE WHEN role = 4 THEN 1 END) as role_4,
            MONTH(created_at) as month,
            COUNT(*) as month_count
        FROM users
        WHERE YEAR(created_at) = YEAR(CURDATE())
        GROUP BY month WITH ROLLUP
    ");

        // Process results
        $count = ['users' => 0, 'posts' => 0, 'posts_read' => 0];
        $userChartData = array_fill(1, 12, 0); // Default 0 for each month

        foreach ($results as $row) {
            if (is_null($row->month)) {
                // ROLLUP row, get totals
                $count['users'] = $row->total;
                $totaluser = $row->role_3;
                $totalpos = $row->role_4;
            } else {
                $userChartData[(int) $row->month] = $row->month_count;
            }
        }

        // Labels
        $monthNames = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
        $userChartLabels = array_values($monthNames);
        $userChartData = array_values($userChartData); // Ensure 0-based index for JS

        // Static Data
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
