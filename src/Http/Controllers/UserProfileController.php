<?php

namespace Lintankwgbn\Adminlte\Http\Controllers;

use Detection\Cache\Cache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lintankwgbn\Adminlte\Agent;

class UserProfileController extends Controller
{
    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('profile.show', [
            'request' => $request,
            'agent' => new Agent(),
            'user' => $request->user(),
        ]);
    }
}
