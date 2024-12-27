<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Imports\MasterUsersImport;
use App\Models\sponcer;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = User::query()
            ->where('role', 3)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('user_id', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('mobilenumber', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->simplePaginate(15);

        return view('admin.users.index', compact('user', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    public function searchSponsor(Request $request)
    {
        $query = $request->get('query');  // Get the search query

        // Fetch users based on name or user_id with LIKE query
        $users = User::where('mobilenumber', 'LIKE', "%$query%")
            ->orWhere('user_id', 'LIKE', "%$query%")
            ->get(['id', 'mobilenumber', 'user_id']); // Return only relevant fields

        // Return the users as JSON for AJAX
        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importUser(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx',
            ]);

            Excel::import(new MasterUsersImport, $request->file('file'));

            return redirect()->back()->with('success', 'Users uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function export()
    {
        $fileName = 'Format.xlsx';
        return Excel::download(new UserExport, $fileName);
    }


    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:255',
            'email' => 'required',
            'password' => 'required|string|min:8',
            'role' => 'required',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        $user->assignRole([$request->role]);

        if ($user->save()) {
            flash()->addSuccess('Customer created successfully.');
            return redirect()->route('admin.users.index');
        }
        flash()->addError('User create fail!');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $sponsor = User::find($user->sponsor_id);

        $userData = User::where('role', 3)->get(['id', 'name', 'user_id']);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }
        return view('admin.users.edit', compact('user', 'userData', 'sponsor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // dd($request->all());
        // Validate the incoming request data
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email,' . $id,
        //     'gender' => 'required|string',
        //     'address' => 'nullable|string|max:255',
        //     'mobilenumber' => 'required|string|max:15', // Adjust max length based on your needs
        //     'city' => 'nullable|string|max:255',
        //     'state' => 'nullable|string|max:255',
        //     'country' => 'nullable|string|max:255',
        //     'pan_no' => 'nullable|string|max:10',
        //     'ifsc' => 'nullable|string|max:11',
        //     'account_no' => 'nullable|string|max:20',
        //     'nominee_name' => 'nullable|string|max:255',
        //     'bank' => 'nullable|string|max:255',
        //     'relation_user' => 'nullable|string|max:255',
        //     'zip' => 'nullable|string|max:10',
        //     'sponsor_id' => 'nullable|string|max:255',
        //     'parent_level' => 'nullable|string|max:255',
        // ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        $request->validate([
            'mobilenumber' => 'required|regex:/^[0-9]{10}$/|unique:users,mobilenumber,' . $user->id,
        ]);

        // Update user attributes
        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->mobilenumber = $request->mobilenumber;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->pan_no = $request->pan_no;
        $user->ifsc = $request->ifsc;
        $user->account_no = $request->account_no;
        $user->nominee_name = $request->nominee_name;
        $user->bank = $request->bank;
        $user->relation_user = $request->relation_user;
        $user->zip = $request->zip;
        $user->sponsor_id = $request->sponsor_id;

        if ($user->save()) {
            $sponcer = Sponsor::where('user_id', $user->id)->first();
            // dd($sponcer); 
            if ($sponcer == null) {
                $sponcer = new Sponsor();
                $sponcer->user_id = $user->id;
                $sponcer->sponsor_id = $request->sponsor_id;
            }
            $sponcer->sponsor_id = $request->sponsor_id;

            if ($sponcer->save()) {
                return redirect()->route('admin.users.index')->with('success', 'Customer updated successfully.');
            }
        }
    }

    public function customUser()
    {
        $userData = User::where('role', 3)->get(['id', 'name', 'user_id']);
        return view('admin.users.custom_user', compact('userData'));
    }
    public function storeCustomUser(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'mobilenumber' => 'required|unique:users,mobilenumber|regex:/^[0-9]{10}$/',
            'sponsor_id' => 'required|exists:users,id',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_id = mt_rand(1000000, 9999999);
        $user->password = Hash::make('123456');
        // dd($user->password);
        $user->mobilenumber = $request->mobilenumber;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->sponsor_id = $request->sponsor_id;
        $user->role = 3;
        $user->assignRole([$user->role]);

        if ($user->save()) {
            $sponcer = new Sponsor();
            $sponcer->user_id = $user->id;
            $sponcer->sponsor_id = $request->sponsor_id;

            if ($sponcer->save()) {
                flash()->addSuccess('Customer User created successfully.');
                return redirect()->back();
            }
        } else {
            flash()->addError('User create fail!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')) {
            $user = User::findOrFail($id);
            $user->status = $status;
            if ($user->save()) {
                flash()->addSuccess('User status updated successfully.');
                return redirect()->back();
            }
            flash()->addError('User status update fail!');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
