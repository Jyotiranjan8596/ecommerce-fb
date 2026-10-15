<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\WhatsappMessageService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'max:30'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100'
            ],
            'mobile' => [
                'required',
                'digits_between:10,15',
                'regex:/^[0-9]+$/',
                'unique:users,mobilenumber',
            ],
            'sponsor_ref' => [
                'nullable',
                'string',
                'max:20',
                'exists:users,user_id',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'gender' => [
                'required',
                'in:male,female,other'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],
            'state' => [
                'required',
                'string',
                'max:50'
            ],
            'city' => [
                'required',
                'string',
                'max:50'
            ],
            'post' => [
                'required',
                'string',
                'max:20'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048' // Max 2MB
            ],
            'terms' => 'accepted',
        ], [
            'mobile.unique' => 'This mobile number is already registered.',
            'sponsor_ref.exists' => 'The Sponsor ID you entered is invalid or does not exist.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd(array_key_exists('terms', $data));
        $imageName = null;
        $referrer = null;

        $defaultSponsorUserId = '9624023';
        $refCode = $data['sponsor_ref'] ?? $defaultSponsorUserId;
        if (! empty($refCode)) {
            $referrer = User::where('user_id', (string) $refCode)->first();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('images'), $imageName);
        }
        $user = User::create([
            'user_id'      => mt_rand(1000000, 9999999),
            'image'        => $imageName,
            'name'         => $data['name'],
            'email'        => $data['email'],
            'gender'       => $data['gender'] == 'male' ? "M" : "F",
            'password'     => Hash::make($data['password']),
            'address'      => $data['address'],
            'mobilenumber' => $data['mobile'],
            'role'         => 3,
            'city'         => $data['city'],
            'state'        => $data['state'],
            'sponsor_id'   => $referrer ? $referrer->id : (User::where('user_id', $defaultSponsorUserId)->value('id') ?? 666666),
        ]);
        // $whatsapp  = new WhatsappMessageService();
        // $msg_reslt = $whatsapp->user_registration($data['name'], $data['mobile']);
        // dd('Comming here');
        // Log::info('registration Result in Route', [$msg_reslt]);
        return $user;
    }

    protected function registered($request, $user)
    {
        session()->flash('registration_success', true);
        session()->flash('registered_user_id', $user->user_id ?? 'N/A');

        return redirect($this->redirectPath());
    }
}
