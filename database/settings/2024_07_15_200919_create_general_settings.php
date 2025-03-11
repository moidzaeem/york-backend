<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', 'York Hospitality');
        $this->migrator->add('general.system_url', 'http://cms.yorkhospitality.com');
    }
};
