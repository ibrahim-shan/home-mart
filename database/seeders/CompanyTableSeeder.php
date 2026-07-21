<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Support\Facades\Artisan;
use Dipokhalder\Settings\Facades\Settings;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('company')->set([
            'company_name'         => 'HomeMart - eCommerce App with Laravel Website & Admin Panel with POS | Inventory Management',
            'company_email'        => 'info@homemart.net',
            'company_calling_code' => '+961',
            'company_phone'        => '81 721 871',
            'company_website'      => 'https://homemart.dev',
            'company_city'         => 'Mirpur 1',
            'company_state'        => 'Dhaka',
            'company_country_code' => 'BGD',
            'company_zip_code'     => '1216',
            'company_latitude'     => '23.7699072',
            'company_longitude'    => '90.3643136',
            'company_address'      => 'HighWay - AL Ghazeieh, Saida Sour, Ghaziyeh'
        ]);

        $envService = new EnvEditor();
        $envService->addData([
            'APP_NAME' => "HomeMart - eCommerce App with Laravel Website & Admin Panel with POS | Inventory Management"
        ]);
        Artisan::call('optimize:clear');
    }
}
