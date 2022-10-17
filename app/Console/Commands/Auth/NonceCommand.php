<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class NonceCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:nonce {name} {nonce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修改指定 Auth 的 nonce 时间, 单位为秒，数字越大，频率越频繁, php artisan auth:nonce $name $nonce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name  = $this->argument('name');
        $nonce = $this->argument('nonce');

        if ($auth = Auth::where('name', $name)->get()->toArray()) {
            Auth::where('name', $name)->update([
                'nonce_throttle' => $nonce,
            ]);
            $this->info('Auth ' . $name . '的 nonce 现在为 ' . $nonce);
        } else {
            $this->error('Auth 名' . $name . '不存在');
        }
    }
}
