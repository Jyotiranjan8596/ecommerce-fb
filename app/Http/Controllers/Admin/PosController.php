<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pos = PosModel::orderBy('id', 'desc')->latest()->simplePaginate(15);
        return view('admin.pos_system.index', compact('pos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = User::select('state')->whereNotNull('state')->distinct()->pluck('state');
        return view('admin.pos_system.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mobilenumber' => 'required|unique:users,mobilenumber|regex:/^[0-9]{10}$/',
        ]);
        $rand_user  = mt_rand(1000000, 9999999);
        $user = new User;
        $user->name = $request->name;
        $user->user_id = $rand_user;
        $user->email = $request->email;
        $user->password = Hash::make('123456');
        $user->mobilenumber = $request->mobilenumber;
        $user->role = 4;
        $user->assignRole([$user->role]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $user->image = $imageName;
        }

        if ($user->save()) {
            $pos = new PosModel;

            $pos->name = $request->name;
            $pos->user_id = $rand_user;
            $pos->email = $request->email;
            $pos->mobilenumber = $request->mobilenumber;
            $pos->transaction_charge = $request->transaction_charge;
            $pos->min_charge = $request->min_charge;
            $pos->max_charge = $request->max_charge;
            $pos->initial_letter_of_invoice = $request->initial_letter_of_invoice;
            $pos->pos_code = $request->pos_code;
            $pos->entity_name = $request->entity_name;
            $pos->entity_address = $request->entity_address;
            $pos->entity_contact = $request->entity_contact;
            $pos->comment = $request->comment;
            $pos->address = $request->address;
            $pos->city = $request->city;
            $pos->state = $request->state;
            $pos->zip = $request->zip;
            $pos->latitude = $request->latitude;
            $pos->longitude = $request->longitude;

            if ($request->hasFile('image')) {
                $pos->image = $imageName;
            }
            if ($pos->save()) {
                $pos_details = $pos->fresh();
                // $logoPath = public_path('images\1721815521.jpg'); // Use forward slashes

                // Check if the logo file exists
                // if (!file_exists($logoPath)) {
                //     throw new \Exception("Logo file does not exist at: " . $logoPath);
                // }

                return redirect()->route('admin.pos_system.index');
            } else {
                $user->delete();
                flash()->addError('Whoops! POS creation failed!');
                return redirect()->back();
            }
        }

        flash()->addError('Whoops! User creation failed!');
        return redirect()->back();
    }

    public function download_qr($id, $name)
    {
        $data = $name . ' | ' . $id;

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            // logoPath: $logoPath,
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center,
        );

        $result = $builder->build();

        return response()->stream(function () use ($result) {
            echo $result->getString();
        }, 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="qr-code-' . $name . '.png"',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pos = PosModel::find($id);
        return view('admin.pos_system.edit', compact('pos'));
    }

    /**
     * Update the specified resource in storage.
     */


    // $request->validate([
    //     'mobilenumber' => 'required|regex:/^[0-9]{10}$/|unique:users,mobilenumber,' . $pos->id,
    // ]);
    public function update(Request $request, $id)
    {
        $pos = PosModel::where('user_id',$id)->first();
        // dd($id,$pos);
    
        if (!$pos) {
            flash()->addError('POS not found!');
            return redirect()->back();
        }
        $pos->name = $request->name;
        $pos->email = $request->email;
        $pos->mobilenumber = $request->mobilenumber;
        $pos->transaction_charge = $request->transaction_charge;
        $pos->min_charge = $request->min_charge;
        $pos->max_charge = $request->max_charge;
        $pos->initial_letter_of_invoice = $request->initial_letter_of_invoice;
        $pos->pos_code = $request->pos_code;
        $pos->comment = $request->comment;
        $pos->address = $request->address;
        $pos->entity_name = $request->entity_name;
        $pos->entity_address = $request->entity_address;
        $pos->entity_contact = $request->entity_contact;
        $pos->city = $request->city;
        $pos->state = $request->state;
        $pos->zip = $request->zip;
        $pos->latitude = $request->latitude;
        $pos->longitude = $request->longitude;
    
        if ($pos->user_id) {
            $user = User::where('user_id',$pos->user_id)->first();
            // dump($user);
            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobilenumber = $request->mobilenumber;
    
                if ($request->hasFile('image')) {
                    $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                    $request->image->move(public_path('images'), $imageName);
                    $pos->image = $imageName;
                    $user->image = $imageName;
                }
    
                $user->save();
            } 
        } 
    
        if ($pos->save()) {
            flash()->addSuccess('POS successfully Updated.');
            return redirect()->route('admin.pos_system.index');
        }
    
        flash()->addError('Whoops! POS Update failed!');
        return redirect()->back();
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
