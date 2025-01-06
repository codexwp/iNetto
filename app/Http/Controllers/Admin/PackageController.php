<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\Package;
use App\Models\ServerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function index()
    {
        $params = ['server_id' => 1];
        $packages = Package::getByConditions($params, true);
        return view('admin.pages.package.index', compact('packages'));
    }

    public function showCreateForm()
    {
        $profiles = ServerProfile::getAll(1);
        return view('admin.pages.package.create', compact('profiles'));
    }

    public function create(PackageRequest $request)
    {
        try{
            $data = $request->validated();
            Package::create($data);
            return redirect()->route('admin.package.index')->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($data)->with('error', $e->getMessage());
        }
    }

    public function showUpdateForm(Package $package)
    {
        $profiles = ServerProfile::getAll(1);
        return view('admin.pages.package.update', compact('package', 'profiles'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        $data = $request->validated();
        try{
            $package->update($request->validated());
            return back()->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($data)->with('error', $e->getMessage());
        }
    }

    public function destroy(Package $package)
    {
        try{
            if($package->users()->count()) {
                throw new \Exception('User exists on this package');
            }
            $package->delete();
            return back()->with('success', 'Successfully deleted');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function  sort(Request $request)
    {
        try{
            $sorts = $request->sorts;
            foreach ($sorts as $sort) {
                $package = Package::find($sort['id']);
                $package->sort = $sort['sort'];
                $package->save();
            }
            return $this->successResponse('Successfully saved');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

}
