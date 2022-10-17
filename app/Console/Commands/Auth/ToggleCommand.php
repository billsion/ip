<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class ToggleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:toggle {name}';

    protected $status = [ '0' => '关闭', '1' => '开启'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开启/关闭指定 Auth, php artisan auth:toggle $name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($auth = Auth::where('name', $name)->get()->toArray()) {
            Auth::where('name', $name)->update([
                'enabled' => ! $auth[0]['enabled'],
            ]);
            $this->info($name . ' 已成功切换为 ' . $this->status[! $auth[0]['enabled']] . ' 状态');
        } else {
            $this->error('Auth 名' . $name . '不存在');
        }
    }
}
