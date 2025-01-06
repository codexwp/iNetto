<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $packages = $user->seller->getPackagesAndDetails(1, true);
        return view('pages.package.index', compact('packages', 'user'));
    }

}
