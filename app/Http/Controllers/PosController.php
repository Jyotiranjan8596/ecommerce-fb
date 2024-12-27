<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
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
}
