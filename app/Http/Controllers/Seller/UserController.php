<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UserRequest;

use App\Models\Seller;
use App\Models\User;
use App\Traits\CsvTransit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use CsvTransit;

    public function index(Request $request)
    {
        $seller = auth('seller')->user();
        $packages = $seller->getPackagesAndDetails(1);
        $params = $request->only(['q', 'id', 'package_id', 'is_active_client', 'is_expired']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $params['seller_id'] = $seller->id;
        $users = User::getByConditions($params, true, 100);
        return view('seller.pages.user.index', compact('users',  'packages'));
    }

    public function showCreateForm()
    {
        $seller = auth('seller')->user();
        return view('seller.pages.user.create', compact('seller'));
    }

    public function create(UserRequest $request)
    {
        try{
            $data = $request->validated();
            $data['seller_id'] = auth('seller')->id();
            User::create($data);
            return redirect()->route('seller.user.index')->with('success', 'Successfully created');
        }
        catch (\Exception $e) {
            return back()->withInput($data)->with('error', $e->getMessage());
        }
    }

    public function details(User $user, Request $request)
    {
        $seller = auth('seller')->user();
        abort_if($user->seller_id != $seller->id, 404);
        return view('seller.pages.user.detail', compact('user'));
    }

    //APIs
    public function fetchDetails(User $user)
    {
        $seller = auth('seller')->user();
        abort_if($user->seller_id != $seller->id, 404);

        $user_info = $user->with(['seller', 'package'])->where('users.id', $user->id)->first();
        $user_info->setAttribute('key', $user_info->secret);
        $payments = $user->payments()
            ->select('payments.id', 'payments.amount', 'payments.status', 'payments.created_at', 'payments.cost')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $seller_packages = $user->seller->getPackagesAndDetails(1);
        return $this->successResponse('Success', [
            'user_info' => $user_info,
            'payments'  => $payments,
            'seller_packages' => $seller_packages
        ]);
    }


    public function updateApi(User $user, UserRequest $request)
    {
        try{
            $user->update($request->validated());
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function othersApi(User $user, $action)
    {
        try{
            $seller = auth('seller')->user();
            abort_if($user->seller_id != $seller->id, 404);

            switch ($action) {
                case 'delete-with-mikrotik':
                    User::destroyWithMikrotik($user);
                    break;
                default:
                    throw new \Exception('Invalid operation');
            }
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function serverPppoeStatus(User $user)
    {
        try{
            return $this->successResponse('Success', ['status' => 1]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

}
