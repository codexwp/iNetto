<?php

namespace App\Console\Commands;

use App\Contracts\SmsGatewayInterface;
use App\Models\Config;
use App\Models\CronJob;
use App\Models\User;
use App\Services\NotifyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyTask extends Command
{
    protected $signature = 'task:daily {--auto=1}';

    protected $description = 'Command description';

    public function handle(SmsGatewayInterface $smsGateway)
    {
        $auto = $this->option('auto');
        if($auto) {
            $now = now();
            if($now->hour == 12) {
                $cron = DB::table('cron_jobs')->whereDate('created_at', $now->today())->where('is_automatic', 1)->first();
                if($cron) { return; }
            } else { return; }
        }

        $autosms_config = Config::get('system_autosms');
        $before_expire_days = intval($autosms_config['before_expire_days'] ?? 5);
        $before_expire_dt = now()->addDays($before_expire_days - 1)->format('Y-m-d') . " 23:59:59";
        $current_dt = now()->format('Y-m-d') . " 23:59:59";

        $expire_soon_users = User::whereNotNull('expire_at')->where('is_expire_notified', 0)
            ->where('is_active_client', 1)
            ->where('expire_at', '>=', $current_dt)
            ->where('expire_at', '<=', $before_expire_dt)
            ->get();

        $notified = 0;
        foreach ($expire_soon_users as $user) {
            try {
                $user->is_expire_notified = 1;
                $user->save();
                $notified++;
                (new NotifyService($smsGateway, $user, null, $autosms_config))->sendRemainderNotification();
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        $expired_users = User::whereNotNull('expire_at')->where('is_active_client', 1)
            ->where('expire_at', '<=', $current_dt)
            ->get();

        $disabled = 0;
        foreach ($expired_users as $user) {
            try {
                $user->is_active_client = 0;
                $user->save();
                $disabled++;
                (new NotifyService($smsGateway, $user, null, $autosms_config))->sendExpiredNotification();
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        $note = "Notified-{$notified}/{$expire_soon_users->count()}, Disabled-{$disabled}/{$expired_users->count()}";

        CronJob::create([
            'is_automatic' => $auto,
            'status' => 1,
            'note' => $note
        ]);

    }
}
