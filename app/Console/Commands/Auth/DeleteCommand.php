<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class DeleteCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:delete {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除指定 Auth, php artisan auth:delete $name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($auth = Auth::where('name', $name)->get()->toArray()) {
            if ($this->confirm("确认要删除名为 $name 的 Auth 吗？")) {
                Auth::where('name', $name)->delete();
                $this->info($name.' 已成功删除');
            }
        } else {
            $this->error('Auth 名'.$name.'不存在');
        }
    }
}
