<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    public function permissionDenied()
    {
        if (auth()->user()?->can('manage_contents')) {
            return redirect()->to('/admin');
        }

        return view('pages.permission_denied');
    }
}
