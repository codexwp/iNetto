<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $seller = auth('seller')->user();
        $packages = $seller->getPackagesAndDetails(1, true);
        return view('seller.pages.package.index', compact('packages'));
    }

}
