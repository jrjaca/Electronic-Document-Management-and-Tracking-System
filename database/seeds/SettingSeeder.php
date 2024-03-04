<?php

use App\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();
        $data = [
            [
                'version' => '1.0',
                'logo' => null,
                'app_name' => null,
                'developed_from' => 'PRO NCR South',
                'developed_by' => 'Virgilio M. Jaca Jr.',
                'company_name' => 'Philippine Health Insurance Corporation',
                'year' => '2020',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
        ];
        Setting::insert($data);
    }
}
