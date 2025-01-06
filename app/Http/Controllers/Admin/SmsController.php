<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\SmsGatewayInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkSmsRequest;
use App\Models\Seller;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->only(['user_id', 'seller_id', 'mobile']);
        foreach ($params as $k => $p) {
            if($p !== '0' && empty($p)) { unset($params[$k]); }
        }
        $sms = Sms::getByConditions($params, true, 50);
        return view('admin.pages.sms.index', compact('sms'));
    }

    public function balance(SmsGatewayInterface $smsGateway)
    {
        $balance = $smsGateway->getBalance();
        if($error = $smsGateway->errorMessage()) {
            return $this->errorResponse($error);
        } else {
            return $this->successResponse('Found', ['balance'=>$balance]);
        }
    }

    public function showSendFrom()
    {
        return view('admin.pages.sms.send');
    }

    public function send(BulkSmsRequest $request, SmsGatewayInterface $smsGateway)
    {
        try{
            $is_enable_sending_sms = config('settings.sms_gateway_default_gateway.is_active', 'false');
            if($is_enable_sending_sms =='false') {
                throw new \Exception("Sending SMS is disabled");
            }

            $data = $request->validated();
            if($data['method'] == 'manual') {
                $numbers = explode(',', $data['mobile']);
            } else {
                if($data['receiver'] == 'seller') {
                    $query = Seller::query();
                } else {
                    $query = User::query();
                    $seller_id = $data['seller_id'] ?? null;
                    if($seller_id) {
                        $query->where('seller_id', $seller_id);
                    }
                    if($data['condition'] == 'enabled') {
                        $query->where('is_active_client', 1);
                    }else if($data['condition'] == 'disabled') {
                        $query->where('is_active_client', 0);
                    }else if($data['condition'] == 'expired') {
                        $query->whereNotNull('expire_at')->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') < ?", [now()]);
                    }else if($data['condition'] == 'not_expired') {
                        $query->whereNotNull('expire_at')->whereRaw("STR_TO_DATE(CONCAT(expire_at, ' 11:59:59'), '%Y-%m-%d %H:%i:%s') >= ?", [now()]);
                    }
                }
                $query->whereNotNull('mobile');
                $users = $query->get();
                $numbers = $query->pluck('mobile')->toArray();
            }

            if(count($numbers) == 0) {
                throw new \Exception('No numbers are found');
            }
            $message =  $data['message'];
            $sms_records = [];

            if($data['method'] == 'manual') {
                foreach ($numbers as $number) {
                    $sms_records[] = [
                        'mobile' => $number,
                        'message' => $message,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } else {
                $id_column_name = $data['receiver'] == 'user' ? 'user_id' : 'seller_id';
                foreach ($users as $user) {
                    $sms_records[] = [
                        'mobile' => $user->mobile,
                        'message' => $message,
                        $id_column_name => $user->id,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            DB::beginTransaction();

            if($smsGateway->sendSms($numbers, $message) == false) {
                throw new \Exception($smsGateway->errorMessage());
            }

            Sms::insert($sms_records);
            DB::commit();
            return $this->successResponse('Successfully requested');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
