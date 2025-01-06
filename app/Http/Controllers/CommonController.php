<?php

namespace App\Http\Controllers;

use App\Models\CronJob;
use App\Models\Package;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\Pear2Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommonController extends Controller
{
    public function fetchInternetSpeed(User $user)
    {
        try{
            $p2s = new Pear2Service(1);
            if(!$p2s->client){
                throw new \Exception($p2s->error);
            }
            $result = $p2s->monitorClient($user->username);
            $data = [
                'downloadSpeed' => $result['traffic']['down'],
                'uploadSpeed' => $result['traffic']['up'],
                'timestamp' => now()->format('H:i:s')
            ];
            return $this->successResponse('Successfully changed', $data);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function executeCron()
    {
        try{
            Artisan::call('task:daily --auto=0');
            return $this->successResponse('Successfully executed');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function getNewExpire(User $user, Package $package)
    {
        $data = PaymentService::prepareExpireDate($user, $package);
        return $this->successResponse('Success', $data);
    }
}
