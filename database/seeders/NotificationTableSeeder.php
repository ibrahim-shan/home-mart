<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Dipokhalder\Settings\Facades\Settings;


class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('notification')->set([
            'notification_fcm_public_vapid_key'    => '',
            'notification_fcm_api_key'             => '',
            'notification_fcm_auth_domain'         => '',
            'notification_fcm_project_id'          => '',
            'notification_fcm_storage_bucket'      => '',
            'notification_fcm_messaging_sender_id' => '',
            'notification_fcm_app_id'              => '',
            'notification_fcm_measurement_id'      => '',
            'notification_fcm_json_file'           => '',
        ]);
    }
}
