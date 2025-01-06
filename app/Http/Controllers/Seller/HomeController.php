<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    public function dashboard()
    {
        $seller = auth('seller')->user();
        $data = array(
            'total_users' => DB::table('users')->where('seller_id', $seller->id)->count(),
            'total_active_users' => DB::table('users')->where('seller_id', $seller->id)->where('is_active_client', 1)->count(),
            'total_inactive_users' => DB::table('users')->where('seller_id', $seller->id)->where('is_active_client', 0)->count(),
            'total_expired_users'  => DB::table('users')->where('seller_id', $seller->id)->whereNotNull('expire_at')->where('expire_at', '<', now()->format('Y-m-d'))->count(),
            'seller_balance' => $seller->balance,

            'total_bill_pay' => DB::table('payments')->where('seller_id', $seller->id)
                ->where('created_at','>=', now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'))
                ->where('created_at','<=', now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'))
                ->where('type', Payment::TYPE_BILL)->sum('amount'),

            'total_seller_costs' => DB::table('payments')->where('seller_id', $seller->id)
                ->where('created_at','>=', now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'))
                ->where('created_at','<=', now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'))
                ->where('type', Payment::TYPE_BILL)->sum('cost'),

            'total_deposit' => DB::table('payments')->where('seller_id', $seller->id)
                ->where('created_at','>=', now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'))
                ->where('created_at','<=', now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'))
                ->where('type', Payment::TYPE_DEPOSIT)->sum('amount'),

            'total_withdraw' => DB::table('payments')->where('seller_id', $seller->id)
                ->where('created_at','>=', now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'))
                ->where('created_at','<=', now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'))
                ->where('type', Payment::TYPE_WITHDRAW)->sum('amount'),
        );

        return view('seller.pages.home.dashboard', $data);
    }
}
