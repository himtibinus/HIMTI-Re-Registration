<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $Section =
            DB::connection('mysql2')->select("select * FROM quartilinformation WHERE IsActive = '1'");
        if ($Section != null) {
            $QuartilActive = $Section[0];
        } else {
            $QuartilActive = [];
        }
        return view('home', ['QuartilActive' => $QuartilActive]);
    }

    /**
     * Get a new CSRF token and ensure that the session is active for more than 2 hours
     */
    public function refreshToken(Request $request)
    {
        session()->regenerate();
        return response()->json(["token" => csrf_token()]);
    }
}