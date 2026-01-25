<?php

    namespace App\Traits;

    use App\Models\GymSetting;
    use Illuminate\Mail\MailServiceProvider;

    trait SmtpSettingsTrait
    {
        public function setMailConfigs()
        {
            $settings = GymSetting::select('mail_driver', 'mail_port', 'mail_host', 'mail_username', 'mail_password', 'mail_encryption', 'mail_name', 'mail_email')->where('detail_id', $this->data['user']->detail_id)->first();
            if (!is_null($settings)) {
                (!is_null($settings->mail_driver)) ? config(['mail.driver' => $settings->mail_driver]) : config(['mail.driver' => 'smtp']);
                config(['mail.host' => $settings->mail_host]);
                config(['mail.port' => $settings->mail_port]);
                config(['mail.username' => $settings->mail_username]);
                config(['mail.password' => $settings->mail_password]);
                config(['mail.encryption' => $settings->mail_encryption]);
                config(['mail.from.name' => $settings->mail_name]);
                config(['mail.from.address' => $settings->mail_email]);
            }
            (new MailServiceProvider(app()))->register();
        }
    }