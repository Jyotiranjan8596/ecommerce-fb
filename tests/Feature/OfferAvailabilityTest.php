<?php

namespace Tests\Feature;

use App\Models\Offer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OfferAvailabilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => 'database/migrations/2026_10_11_000000_create_offers_table.php',
            '--force' => true,
        ]);
    }

    public function test_published_offers_are_filtered_by_audience_and_schedule(): void
    {
        Offer::create([
            'title' => 'Prime only',
            'description' => 'A Prime customer offer.',
            'audiences' => ['prime'],
            'is_active' => true,
        ]);
        Offer::create([
            'title' => 'POS and non-Prime',
            'description' => 'A shared offer.',
            'audiences' => ['pos', 'non_prime'],
            'is_active' => true,
        ]);
        Offer::create([
            'title' => 'Unpublished',
            'description' => 'Not available to customers.',
            'audiences' => ['prime', 'pos'],
            'is_active' => false,
        ]);
        Offer::create([
            'title' => 'Scheduled',
            'description' => 'Starts later.',
            'audiences' => ['prime'],
            'starts_at' => now()->addDay(),
            'is_active' => true,
        ]);
        Offer::create([
            'title' => 'Expired',
            'description' => 'Ended earlier.',
            'audiences' => ['prime'],
            'ends_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $baseQuery = Offer::query()->published();

        $this->assertSame(1, (clone $baseQuery)->whereJsonContains('audiences', 'prime')->count());
        $this->assertSame(1, (clone $baseQuery)->whereJsonContains('audiences', 'pos')->count());
        $this->assertSame(1, (clone $baseQuery)->whereJsonContains('audiences', 'non_prime')->count());
    }
}
