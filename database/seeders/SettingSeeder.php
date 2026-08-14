<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Lit Lyrics',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'site_description',
                'value' => 'Your daily source for liturgical lyrics.',
                'group' => 'general',
                'type' => 'textarea',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@litlyrics.com',
                'group' => 'general',
                'type' => 'email',
            ],
            [
                'key' => 'items_per_page',
                'value' => '10',
                'group' => 'appearance',
                'type' => 'number',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'group' => 'general',
                'type' => 'boolean',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'group' => 'appearance',
                'type' => 'image',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'api_is_free',
                'value' => '1',
                'group' => 'api',
                'type' => 'boolean',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@litlyrics.com',
                'group' => 'contact',
                'type' => 'email',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 019-2834',
                'group' => 'contact',
                'type' => 'text',
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Liturgy Way, Hymn City, HC 94025',
                'group' => 'contact',
                'type' => 'textarea',
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
