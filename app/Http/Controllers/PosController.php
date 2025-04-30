<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        return view('pos.index', compact('userId', 'user_profile'));
    }
    public function userList(Request $request)
    {
        $query = DB::table('users')->where('role', 3);
        if ($request->filled('search_by') && $request->filled('search_term')) {
            $searchBy = $request->input('search_by');
            $searchTerm = $request->input('search_term');

            if ($searchBy == 'user_id') {
                $query->where('user_id', $searchTerm);
            } elseif ($searchBy == 'name') {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            } elseif ($searchBy == 'mobilenumber') {
                $query->where('mobilenumber', 'like', '%' . $searchTerm . '%');
            }
        }

        $users = $query->orderBy('id', 'desc')->simplePaginate(15);

        return view('pos.user_list', compact('users'));
    }
    public function changepassword()
    {
        return view('pos.changepassword');
    }
    public function newpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:5|confirmed',
        ]);
        $newpass = Auth::user();
        $newpass->password = Hash::make($request->password);
        if ($newpass->save()) {
            flash()->addSuccess('Your password has been successfully updated.');
            return redirect()->route('pos.index');
        }
    }

    public function verifyAllCustomer()
    {
        try {
            $posId = auth()->user()->user_id;
            // dd($posId); 
            $pos = PosModel::where('user_id', $posId)->first();
            // dd($pos);
            if ($pos) {
                $posId = $pos->id;
                // dd($posId);
            }
            // Update the status for wallets linked to the specified pos_id
            $updatedRows = Wallet::where('pos_id', $posId)->update([
                'status' => 1,
            ]);
            if($updatedRows > 0){
                return response()->json([
                    'code' => 200,
                    'message' => "Costumers have been successfully verified.",
                ]);
            }else{
                return response()->json([
                    'code' => 200,
                    'message' => "Already Verified",
                ]);
            }
            // Return success response
            
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying customers.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
