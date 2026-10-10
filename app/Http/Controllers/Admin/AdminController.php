<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId = $user_profile->id;

        $year = now()->year;
        $months = range(1, (int) now()->month);
        $userChartLabels = array_slice(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0, count($months));
        $salesChartLabels = $userChartLabels;

        $totaluser = User::where('role', 3)->count();
        $totalpos = PosModel::count();
        $count = ['posts_read' => 0, 'comments' => 0];

        $monthlyUsers = User::query()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('role', 3)
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        $monthlySalesAndRewards = DB::table('wallets')
            ->selectRaw("MONTH(transaction_date) as month, SUM(billing_amount) as sales, SUM(CASE WHEN pay_by = 'reward' THEN reward_amount ELSE 0 END) as rewards")
            ->whereYear('transaction_date', $year)
            ->groupByRaw('MONTH(transaction_date)')
            ->get()
            ->keyBy('month');

        $userChartData = array_map(fn ($month) => (int) ($monthlyUsers[$month] ?? 0), $months);
        $salesChartData = array_map(fn ($month) => round((float) ($monthlySalesAndRewards[$month]->sales ?? 0), 2), $months);
        $rewardChartData = array_map(fn ($month) => round((float) ($monthlySalesAndRewards[$month]->rewards ?? 0), 2), $months);

        $todaySales = (float) DB::table('wallets')
            ->whereDate('transaction_date', today())
            ->sum('billing_amount');

        $newPosts = 0;
        $topPosts = 0;

        return view('admin.index', compact(
            'count',
            'totaluser',
            'totalpos',
            'todaySales',
            'year',
            'userId',
            'user_profile',
            'newPosts',
            'topPosts',
            'userChartLabels',
            'userChartData',
            'salesChartLabels',
            'salesChartData',
            'rewardChartData'
        ));
    }



    public function offer()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;

        return view('admin.offer.index', compact('userId', 'user_profile'));
    }
}
