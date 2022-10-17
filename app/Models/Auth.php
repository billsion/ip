<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auth.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth query()
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name 应用名称
 * @property string $app_key 应用key
 * @property string $app_secret 应用密钥
 * @property string|null $memo 备注
 * @property int $enabled 是否开启
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereAppKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereAppSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereUpdatedAt($value)
 */
class Auth extends Model
{
    public $timestamps = true;

    public $table = 'auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'app_key',
        'app_secret',
        'memo',
        'nonce',
        'enabled',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        //'app_key',
        //'app_secret',
    ];

    /**
     * 生成随机字符串.
     *
     * @param int $length 字符串长度
     * @param bool $u 标明一个字符串是否能连续显示，默认可以连续显示, FALSE 连续显示，TRUE 不能连续显示
     */
    public static function rand_chars(int $length = 8, $u = false): string
    {
        $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $u = false;
        if (! $u) {
            for ($s = '', $i = 0, $z = strlen($c) - 1; $i < $length; $x = rand(0, $z), $s .= $c[$x], $i++);
        } else {
            for ($i = 0, $z = strlen($c) - 1, $s = $c[rand(0, $z)], $i = 1; $i != $length; $x = rand(0, $z), $s .= $c[$x], $s = ($s[$i] == $s[$i - 1] ? substr($s, 0, -1) : $s), $i = strlen($s));
        }

        return $s;
    }
}
