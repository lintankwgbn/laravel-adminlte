<?php

namespace Lintankwgbn\Adminlte\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Lintankwgbn\Adminlte\Adminlte;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $termsFile = Adminlte::localizedMarkdownPath('terms.md');

        return view('terms', [
            'terms' => Str::markdown(file_get_contents($termsFile)),
        ]);
    }
}
