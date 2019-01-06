<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $pairLink = "fingervault://pages/create";
        $pairLink .= "?name=" . rawurlencode(env('APP_NAME'));
        $pairLink .= "&host=" . rawurlencode(env('APP_HOST'));
        $pairLink .= "&url=" . rawurlencode(env('APP_URL') . '/public');
        $pairLink .= "&login=" . rawurlencode($user->email);
        $pairLink .= "&userToken=" . rawurlencode($user->fingervault_user_token);
        $pairLink .= "&tokenEndpoint=" . rawurlencode('/api/fingervault/token');
        $pairLink .= "&loginEndpoint=" . rawurlencode('/fingervault/login/{loginToken}');


        return view('profile', ['user' => $user, 'pairLink' => $pairLink]);
    }
}
