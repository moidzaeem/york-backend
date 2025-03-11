<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail_server.host', 'mail.yorkhospitality.com');
        $this->migrator->add('mail_server.email', 'info@yorkhospitality.com');
        $this->migrator->add('mail_server.password', '');
    }
};
