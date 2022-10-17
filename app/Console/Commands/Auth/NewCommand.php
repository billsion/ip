<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class NewCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:new {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新建 Auth, php artisan auth:new $name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $date_time = (new Carbon())->toDateTimeString();

        if (Auth::where('name', $name)->get()->toArray()) {
            $this->error('Auth 名' . $name . '已存在，无法添加');
        } else {
            Auth::insert([
                'name'           => $name,
                'app_key'        => Str::random(32),
                'app_secret'     => Str::random(32),
                'nonce_throttle' => env('NONCE_VALID_DURATION'),
                'memo'           => '',
                'enabled'        => 1,
                'created_at'     => $date_time,
                'updated_at'     => $date_time,
            ]);

            $this->info('Auth 名 ' . $name . '添加成功');
        }
    }
}
