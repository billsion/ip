<?php

namespace App\Console\Commands\Auth;

use App\Models\Auth;
use Illuminate\Console\Command;

/**
 * Class deletePostsCommand.
 *
 * @category Console_Command
 */
class RenameCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'auth:rename {oldname} {newname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '对 Auth 重命名 php artisan auth:rename $oldname $newname';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldname = $this->argument('oldname');
        $newname = $this->argument('newname');

        if (Auth::where('name', $newname)->get()->toArray()) {
            $this->error('Auth 名'.$newname.'已被使用到，无法更名为改名称');
        } else {
            Auth::where('name', $oldname)->update(['name' => $newname]);

            $this->info($oldname.' 改名为 '.$newname);
        }
    }
}
