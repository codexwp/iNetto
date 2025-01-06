<?php

namespace App\Traits;

use App\Models\SendMailHistory;
use App\Models\SendMailTemplate;
use App\Models\Setting;
use App\Models\User;
use App\Services\QrService;
use Illuminate\Support\Facades\Log;

trait SendGridTransit
{
    /**
     * @var int 認証メール
     */
    public static int $verify_email = 1;
    /**
     * @var int パスワードリセットメール
     */
    public static int $password_reset = 2;
    /**
     * @var int パスワードリセット完了メール
     */
    public static int $password_reset_completed = 3;
    /**
     * @var int 登録完了メール
     */
    public static int $register_completed = 4;
    /**
     * @var int 更新完了メール
     */
    public static int $update_completed = 5;
    /**
     * @var int Reset request from user
     */
    public static int $password_reset_request = 6;
    /**
     * @var int Reset request from user
     */
    public static int $remind_verify_email = 7;


    public static function sendMail(User $user, int $type, array $data = [])
    {
        $event = Setting::getByPrefix(Setting::EVENT_PREFIX);
        $view = '';
        $with = [];

        switch ($type)
        {
            case self::$verify_email:
                $title = 'ご登録メールアドレスのご確認';
                $view = 'mails.verify_email';
                $with = [
                    'url' => self::verificationUrl($user),
                ];
                break;

            case self::$remind_verify_email:
                $title = '申し込み本登録のお願い';
                $view = 'mails.remind_verify_email';
                $with = [
                    'url' => self::verificationUrl($user),
                ];
                break;

            case self::$password_reset_request:
                $title = 'パスワード変更用URL発行のお知らせ';
                $view = 'mails.reset_password';
                $with = [
                    'url' => self::resetLink($user, $data['token']),
                ];
                break;

            case self::$password_reset:
                $title = 'パスワード変更のお知らせ';
                $view = 'mails.password_reset';
                $with = [
                    'email' => $user->email,
                    'password' => $data['password']
                ];
                break;

            case self::$password_reset_completed:
                $title = 'パスワード変更完了のお知らせ';
                $view = 'mails.password_reset_completed';
                break;

            case self::$register_completed:
                $title = 'ご登録完了のお知らせ';
                $view = 'mails.register_completed';
                $with = [
                    'url' => QrService::createUrl($user->encrypted_id),
                    'email' => $user->email,
                    'password' => $user->password,
                ];
                break;

            case self::$update_completed:
                $title = '登録情報変更完了のお知らせ';
                $view = 'mails.update_completed';
                break;
        }

        $title = '【'. $event['event_name'] .'】' . $title;
        $with['event'] = $event;

        $sendGrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(env('MAIL_FROM_ADDRESS'), $event['event_host_name']);
        $email->addTo($user->email);
        $email->setSubject($title);
        $email->addContent(
            "text/html", strval(view($view, $with))
        );

        return $sendGrid->send($email);
    }

    private static function verificationUrl($user)
    {
        $now = now();
        $data = $user->email . $now . $user->created_at->format('Y-m-d H:i:s');
        $token = hash('sha256', $data);
        $user->register_token = $token;
        $user->register_token_at = $now;
        $user->save();
        return env('APP_URL') . '/email/verify/' . $token;
    }

    private static function resetLink(User $user, string $token)
    {
        return url(route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset()
        ], false));
    }

    public static function bulkSendMail(int $template_id)
    {
        $template = SendMailTemplate::find($template_id);
        $users = User::getMailableUsers(json_decode($template->search_conditions, true));
        $event = Setting::getByPrefix(Setting::EVENT_PREFIX);

        foreach ($users as $user) {
            // ブラックリストユーザーには送らない
            if ($user->blacklisted_at) {
                continue;
            }
            // タグを変換
            $title = SendMailHistory::convertText($user, $template->title, false);
            $body = SendMailHistory::convertText($user, $template->text);
            try {
                $sendGrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom(env('MAIL_FROM_ADDRESS'), $event['event_host_name']);
                $email->addTo($user->email);
                $email->setSubject('【'. $event['event_name'] .'】' . $title);
                $email->addContent(
                    "text/html", $body
                );
                $sendGrid->send($email);
                SendMailHistory::createData($user, $template->title, $template->id);
            } catch (\Exception $e) {
                SendMailHistory::createData($user, $template->title, $template->id, true);
                Log::error('メール送信に失敗しました。 申込者ID：' . $user->id);
                Log::error($e->__toString());
                continue;
            }
        }
    }
}
