<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class ResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:reset {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置指定 Auth Secret, php artisan auth:reset $name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (Auth::where('name', $name)->get()->toArray()) {
            $app_secret = Str::random(32);
            Auth::where('name', $name)->update([
                'app_secret' => $app_secret,
            ]);
            $this->info($name . ' 重置成功, Secret 为' . $app_secret);
        } else {
            $this->error('Auth 名' . $name . '不存在');
        }
    }
}
