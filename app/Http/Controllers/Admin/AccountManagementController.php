<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosModel;
use App\Models\User;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    public function updateCustomerStatus(Request $request, User $user)
    {
        abort_unless((int) $user->role === 3, 404);

        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        return $this->saveStatus($user, (int) $validated['status'], 'Customer');
    }

    public function updatePosStatus(Request $request, PosModel $pos)
    {
        $user = $this->posAccount($pos);
        $validated = $request->validate([
            'status' => ['required', 'in:0,1'],
        ]);

        return $this->saveStatus($user, (int) $validated['status'], 'POS');
    }

    public function updateCustomerPassword(Request $request, User $user)
    {
        abort_unless((int) $user->role === 3, 404);

        return $this->savePassword($request, $user, 'Customer');
    }

    public function updatePosPassword(Request $request, PosModel $pos)
    {
        return $this->savePassword($request, $this->posAccount($pos), 'POS');
    }

    private function posAccount(PosModel $pos): User
    {
        $user = $pos->user;
        abort_unless($user && (int) $user->role === 4, 404);

        return $user;
    }

    private function saveStatus(User $user, int $status, string $accountType)
    {
        $user->status = $status;

        if (! $user->save()) {
            return back()->with('error', "{$accountType} account status could not be updated.");
        }

        $action = $status === 1 ? 'activated' : 'suspended';

        return back()->with('success', "{$accountType} account {$action} successfully.");
    }

    private function savePassword(Request $request, User $user, string $accountType)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = $validated['password'];

        if (! $user->save()) {
            return back()->with('error', "{$accountType} password could not be changed.");
        }

        return back()->with('success', "{$accountType} password changed successfully.");
    }
}
