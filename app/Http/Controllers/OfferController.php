<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ((int) $user->role === 4) {
            $audience = 'pos';
            $layout = 'pos.layouts.master';
        } else {
            $audience = $user->is_prime_member ? 'prime' : 'non_prime';
            $layout = 'frontend.dashboard.layouts.master';
        }

        $offers = Offer::query()
            ->published()
            ->whereJsonContains('audiences', $audience)
            ->latest()
            ->paginate(12);

        return view('offers.index', compact('offers', 'layout'));
    }
}
