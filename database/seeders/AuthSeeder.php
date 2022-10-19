<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_time = (new Carbon())->toDateTimeString();

        DB::table('auth')->insert([
            'name'           => 'abc',
            'app_key'        => Str::random(32),
            'app_secret'     => Str::random(32),
            'nonce_throttle' => env('NONCE_VALID_DURATION'),
            'memo'           => '',
            'enabled'        => 1,
            'created_at'     => $date_time,
            'updated_at'     => $date_time,
        ]);
    }
}
