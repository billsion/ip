<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    /*public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }*/

    /**
     * 作简单的请求头格式判断.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $headers      = $request->headers;
        $sign_headers = env('APP_SIGN_HEADERS');

        $sign_headers = explode(',', $sign_headers);

        // 如果请求头中缺少 .env 文件中设定的 APP_SIGN_HEADERS 值，直接返回
        foreach ($sign_headers as $_key => $_header) {
            if (! $headers->has($_header)) {
                return response([
                    'msg'  => 'invalid request: missing ' . $_header,
                    'code' => 'err',
                ], 200, ['x-ca-error-message' => 'invalid request: missing ' . $_header]);
            }
        }

        // 对请求时间作判断，请求时间间隔依赖于 .ENV 文件中的时间，必须小于 .ENV 文件中设置的时间, 否则直接返回
        if (time() - $headers->get('x-ca-timestamp') < env('REQUEST_VALID_DURATION')) {
            return $next($request);
        } else {
            return response([
                'msg'  => 'invalid request: timeout',
                'code' => 'err',
            ], 200, ['x-ca-error-message' => 'invalid request: timeout']);
        }
    }
}
