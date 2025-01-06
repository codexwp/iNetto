<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $seller = auth()->user()->seller;
        $company = config('settings.system_general');
        return view('pages.support.index', compact('seller', 'company'));
    }
}
