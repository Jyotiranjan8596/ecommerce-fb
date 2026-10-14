<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(): View
    {
        $offers = Offer::query()->latest()->paginate(12);
        $activeCount = Offer::query()->published()->count();
        $scheduledCount = Offer::query()
            ->where('is_active', true)
            ->where('starts_at', '>', now())
            ->count();

        return view('admin.offers.index', compact('offers', 'activeCount', 'scheduledCount'));
    }

    public function create(): View
    {
        return view('admin.offers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $offer = Offer::create($this->validatedData($request));

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer "' . $offer->title . '" has been created.');
    }

    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer): RedirectResponse
    {
        $offer->update($this->validatedData($request));

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer "' . $offer->title . '" has been updated.');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        $title = $offer->title;
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer "' . $title . '" has been deleted.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:10000'],
            'audiences' => ['required', 'array', 'min:1'],
            'audiences.*' => ['required', 'string', 'distinct', Rule::in(array_keys(Offer::AUDIENCES))],
            'redemption_instructions' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'cta_label' => ['nullable', 'string', 'max:60', 'required_with:cta_url'],
            'cta_url' => ['nullable', 'url', 'max:2048', 'required_with:cta_label'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
