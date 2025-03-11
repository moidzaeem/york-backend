<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{

    public string $company_name;
    public string $system_url;

    public static function group(): string
    {
        return 'general';
    }
}
