<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SellerRequest;
use App\Models\Seller;
use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->only(['q', 'id']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $sellers = Seller::getByConditions($params, true, 50);
        $tariffs = Tariff::getAll();
        return view('admin.pages.seller.index', compact('sellers', 'tariffs'));
    }

    public function showCreateForm()
    {
        $tariffs = Tariff::getAll();
        return view('admin.pages.seller.create', compact('tariffs'));
    }

    public function create(SellerRequest $request)
    {
        try{
            $data = $request->validated();
            Seller::create($data);
            return redirect()->route('admin.seller.index')->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($data)->with('error', 'Unable to create');
        }
    }

    public function details(Seller $seller)
    {
        $tariffs = Tariff::getAll();
        return view('admin.pages.seller.detail', compact('seller', 'tariffs'));
    }

    //APIs
    public function fetchDetails(Seller $seller)
    {
        $payments = $seller->payments()
            ->select('payments.id', 'payments.amount', 'payments.cost', 'payments.status', 'payments.created_at', 'payments.type')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $sellers = Seller::where('id', '<>', $seller->id)->get();
        $seller->setAttribute('user_count', $seller->users()->count());
        $seller->setAttribute('tariff', $seller->tariff);

        return $this->successResponse('Success', [
            'seller' => $seller,
            'sellers'=> $sellers,
            'payments'  => $payments,
        ]);
    }

    public function fetchPackages(Seller $seller)
    {
        try{
            return $this->successResponse('Success', ['packages' => $seller->getPackagesAndDetails(1)]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function fetchUsers(Seller $seller)
    {
        try{
            return $this->successResponse('Success', ['users' => $seller->users]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updateApi(Seller $seller, SellerRequest $request)
    {
        try{
            $seller->update($request->validated());
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function destroy(Seller $seller)
    {
        try{
            $users_count = $seller->users->count();
            if($users_count) {
                throw new \Exception("Seller have $users_count users. Transfer first.");
            }

            $seller->delete();
            return $this->successResponse('Successfully delete');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function usersTransfer(Seller $seller, Request $request)
    {
        try{
            $new_seller_id = $request->new_seller_id;
            $new_seller = Seller::findById($new_seller_id);
            if(empty($new_seller_id) || is_null($new_seller)) {
                throw new \Exception("Invalid new seller");
            }

            $seller->users()->update(['seller_id'=> $new_seller_id]);
            return $this->successResponse('Successfully transferred');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }


}
