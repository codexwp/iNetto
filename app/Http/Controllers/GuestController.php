<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Traits\Install;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    use Install;

    public function index()
    {
        $packages = Package::getByConditions(['is_home_display'=>1]);
        return view('pages.guest.index', compact('packages'));
    }

    public function showInstallForm()
    {
        // PHP version check
        $currentVersion = phpversion();
        if (!version_compare($currentVersion, '8.1', '>=')) {
            die('Minimum required PHP version is 8.1. Current version is: ' . $currentVersion);
        }

        $status = $this->checkInstall();

        //If already installed, redirect to home
        if($status['status'] && empty(old('mobile'))){
            return redirect('/');
        }

        return view('pages.guest.install');
    }

    public function install(Request $request)
    {
        $result = $this->doInstall($request);
        return back()->with($result['status'], $result['message'] ?? '')->withInput($request->all());
    }

}
