<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Package;
use App\Models\State;
use App\Models\Tariff;
use App\Models\Town;
use Cwp\Address\Library\BdAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TariffController extends Controller
{
    public function index()
    {
        $packages = Package::getAll(1);
        $tariffs = Tariff::getAll();
        return view('admin.pages.tariff.index', compact('packages', 'tariffs'));
    }

    public function showCreateForm()
    {
        return view('admin.pages.tariff.create');
    }

    public function create(Request $request)
    {
        try{
            if(is_null($request->name) || empty($request->name))
                throw new \Exception('Name field is empty');

            Tariff::create(['name' => $request->name ]);
            return redirect()->route('admin.tariff.index')->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($request->all())->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        if(Tariff::updateTariffPackageCommissions($request->tpc))
            return back()->with('success', 'Successfully updated');

        return back()->with('error', 'Unable to update');
    }

    public function destroy(Tariff $tariff)
    {
        try{
            if($count = $tariff->sellers()->count()) {
                throw new \Exception("Tariff has $count sellers");
            }
            $tariff->delete();
            return back()->with('success', 'Successfully deleted');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
