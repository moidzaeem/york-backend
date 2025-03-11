<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social_media.facebook', '');
        $this->migrator->add('social_media.instagram', '');
        $this->migrator->add('social_media.twitter', '');
        $this->migrator->add('social_media.youtube', '');
    }
};
