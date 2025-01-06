<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth('admin')->user();
        return view('admin.pages.profile.index', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        try{
            $auth_user = auth('admin')->user();
            $auth_user->update($request->validated());
            return back()->with('success', 'Successfully updated');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showChangePasswordForm()
    {
        return view('admin.pages.profile.change_password');
    }

    public function changePassword(ProfileRequest $request)
    {
        try{
            $auth_user = auth('admin')->user();
            $auth_user->update(['password' => $request->password]);
            return back()->with('success', 'Successfully updated');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
