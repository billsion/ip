<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class IPValidationMiddleware
{
    /**
     * 作简单的 IP 格式判断.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function handle($request, Closure $next)
    {
        $ipaddress = $request->route('ip');
        $data      = ['ip' => $ipaddress];
        $validator = Validator::make($data, [
            'ip' => ['required', 'ip'],
        ]);

        if ($validator->fails()) {
            return response([
                'msg'  => 'IP 地址格式不正确',
                'code' => 'err',
            ], 200, ['x-ca-error-message' => 'invalid request IP address']);
        } else {
            return $next($request);
        }
    }
}
