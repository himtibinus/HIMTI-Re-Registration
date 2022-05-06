<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string'],
            'line' => ['nullable', 'string'],
            'whatsapp' => ['nullable', 'string'],
            'university_id' => ['required', 'numeric'],
            'nim' => ['nullable', 'numeric'],
            'binus_regional' => ['nullable', 'string'],
            'fyp_batch' => ['nullable', 'string'],
            'major_name' => ['nullable', 'string'],
            'new_university' => ['nullable', 'string']
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
        if ($data['new_university'] != ""){
            $data['university_id'] = DB::table('universities')->insertGetId(['name' => $data['new_university']]);
        }

        if ($data['university_id'] == 2 || $data['university_id'] == 3) $data['university_id'] = 1;
        $new_user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'university_id' => $data['university_id'],
            'password' => Hash::make($data['password'])
        ]);

        $user_properties = DB::table('user_properties');

        // Save phone, LINE, WhatsApp information
        $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'contacts.phone', 'value' => $data['phone']]);
        if ($data['line']) $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'contacts.line', 'value' => $data['line']]);
        if ($data['whatsapp']) $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'contacts.whatsapp', 'value' => $data['whatsapp']]);

        // Save BINUSIAN status
        if ($data['university_id'] == 4){
            $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'binusian.regional', 'value' => $data['binus_regional']]);
            $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'binusian.year', 'value' => '20' . substr($data['nim'], 0, 2)]);
            if ($data['fyp_batch'] > 0) $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'binusian.fyp.batch', 'value' => $data['fyp_batch']]);
        }

        // Save major / study program
        if ($data['nim']) $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'university.nim', 'value' => $data['nim']]);
        if ($data['major']) $user_properties->insert(['user_id' => $new_user->id, 'field_id' => 'university.major', 'value' => $data['major']]);

        return $new_user;
    }
}