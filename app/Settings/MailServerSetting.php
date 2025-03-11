<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailServerSetting extends Settings
{

    public string $host;
    public string $email;
    public string $password;

    public static function group(): string
    {
        return 'mail_server';
    }
}
