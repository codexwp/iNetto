<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth('seller')->user();
        return view('seller.pages.profile.index', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        try{
            $auth_user = auth('seller')->user();
            $auth_user->update($request->validated());
            return back()->with('success', 'Successfully updated');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showChangePasswordForm()
    {
        return view('seller.pages.profile.change_password');
    }

    public function changePassword(ProfileRequest $request)
    {
        try{
            $auth_user = auth('seller')->user();
            $auth_user->update(['password' => $request->password]);
            return back()->with('success', 'Successfully updated');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
