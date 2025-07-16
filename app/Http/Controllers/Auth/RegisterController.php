<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\AiSensyService;
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
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required'],
            'mobile'  => ['required'],
            'gender'  => ['required'],
            'address' => ['required'],
            'state'   => ['required'],
            'city'    => ['required'],
            'post'    => ['required'],
            'image'   => ['nullable'],
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
        $imageName = null;

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
            'password'     => Hash::make('123456'),
            'address'      => $data['address'],
            'mobilenumber' => $data['mobile'],
            'role'         => 3,
            'city'         => $data['city'],
            'state'        => $data['state'],
            'sponsor_id'   => 666666,
        ]);
        $params = [
            $data['name'],
            $data['mobile'],
        ];
        $whatsapp  = new AiSensyService();
        $msg_reslt = $whatsapp->send_registration($data['mobile'], $params);
        Log::info('registration Result in Route', [$msg_reslt]);
        return $user;
    }
}
