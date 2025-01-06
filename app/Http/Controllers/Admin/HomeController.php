<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    public function dashboard()
    {
        $start_date_r = request('start') ?? now()->startOfMonth()->startOfDay()->format('Y-m-d');
        $start_date = $start_date_r . " 00:00:00";
        $end_date_r = request('end') ?? now()->endOfMonth()->endOfDay()->format('Y-m-d');
        $end_date = $end_date_r . " 23:59:59";
        $seller_id = request('seller');

        if($seller_id) {
            $data = array(
                'total_users' => DB::table('users')->where('seller_id', $seller_id)->count(),
                'total_active_users' => DB::table('users')->where('seller_id', $seller_id)->where('is_active_client', 1)->count(),
                'total_inactive_users' => DB::table('users')->where('seller_id', $seller_id)->where('is_active_client', 0)->count(),
                'total_expired_users'  => DB::table('users')->where('seller_id', $seller_id)->whereNotNull('expire_at')->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') < ?", [now()])->count(),
                'seller_balance' => DB::table('sellers')->where('id', $seller_id)->sum('balance'),
                'total_bill_paid_by_seller' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_SELLER)
                    ->where('type', Payment::TYPE_BILL)->sum('amount'),

                'total_bill_paid_by_user' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_USER)
                    ->where('type', Payment::TYPE_BILL)->sum('amount'),

                'total_seller_costs' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_SELLER)
                    ->where('type', Payment::TYPE_BILL)->sum('cost'),

                'total_costs' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_BILL)->sum('cost'),

                'total_commission' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_COMMISSION)->sum('amount'),

                'total_deposit' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_DEPOSIT)->sum('amount'),

                'total_withdraw' => DB::table('payments')
                    ->where('seller_id', $seller_id)
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_WITHDRAW)->sum('amount'),
            );
        } else {
            $data = array(
                'total_users' => DB::table('users')->count(),
                'total_active_users' => DB::table('users')->where('is_active_client', 1)->count(),
                'total_inactive_users' => DB::table('users')->where('is_active_client', 0)->count(),
                'total_expired_users'  => DB::table('users')->whereNotNull('expire_at')->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') < ?", [now()])->count(),
                'seller_balance' => DB::table('sellers')->sum('balance'),
                'total_bill_paid_by_seller' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_SELLER)
                    ->where('type', Payment::TYPE_BILL)->sum('amount'),

                'total_bill_paid_by_user' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_USER)
                    ->where('type', Payment::TYPE_BILL)->sum('amount'),

                'total_seller_costs' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('bill_pay_by', Payment::USER_TYPE_SELLER)
                    ->where('type', Payment::TYPE_BILL)->sum('cost'),

                'total_costs' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_BILL)->sum('cost'),

                'total_commission' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_COMMISSION)->sum('amount'),

                'total_deposit' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_DEPOSIT)->sum('amount'),

                'total_withdraw' => DB::table('payments')
                    ->where('created_at','>=', $start_date)
                    ->where('created_at','<=', $end_date)
                    ->where('type', Payment::TYPE_WITHDRAW)->sum('amount'),
            );
        }

        $data['total_packages'] = DB::table('packages')->count();
        $data['total_tariffs'] = DB::table('tariffs')->count();
        $data['total_sellers'] = DB::table('sellers')->count();
        $data['sellers'] = DB::table('sellers')->orderBy('id', 'asc')->get();
        $data['start_date'] = $start_date_r;
        $data['end_date'] = $end_date_r;

        return view('admin.pages.home.dashboard', $data);
    }
}
