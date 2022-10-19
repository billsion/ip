<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class ListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:list {name?}';

    protected $fields = ['name', 'app_key', 'app_secret', 'nonce_throttle', 'memo', 'enabled'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '列出所有 Auth, php artisan auth:list || php artisan auth:list $name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $auth = new Auth();
        if ($name = $this->argument('name')) {// 指明参数显示该参数对应的 name
            $auth = $auth->where('name', $name);
            $this->table(
                $this->fields,
                $auth->select($this->fields)->get()->toArray()
            );
        } else { // 否则列出所有的 auth
            $this->table(
                $this->fields,
                $auth->select($this->fields)->get()->toArray()
            );
        }
    }
}
