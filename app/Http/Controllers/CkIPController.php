<?php

namespace App\Http\Controllers;

use App\Helpers\{Sign, UUID};
use App\Models\Auth;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Cache, URL};
use ipip\db\City;

class CkIPController extends Controller
{
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 定位 IP.
     */
    public function check(Request $request): JsonResponse
    {
        $sign    = base64_decode($request->headers->get('x-ca-signature'));
        $app_key = $request->headers->get('x-ca-key');
        $nonce   = $request->headers->get('x-ca-nonce');

        // 判断 nonce 是否在有效期内
        // 如果请求的 nonce 不存在，设置 nonce
        if (! Cache::store('redis')->has($nonce)) {
            Cache::store('redis')->set($nonce, time(), env('NONCE_VALID_DURATION'));
        // 作 IP 地址定位判断
        } else {
            return response()->json(['msg' => 'invalid nonce', 'code' => 'ok'])
            ->header('x-ca-error-message', 'invalid nonce');
        }

        // 数据库查询请求带上的 nonce 是否存在（推荐使用Redis，自带TTL功能）。
        // 如果不存在，且请求时间有效则为合法请求，同时将 nonce 写入，并记录时间；如果不存在，且请求时间超出规定时限，判断为恶意请求。
        // 如果已经存在，判断为恶意请求。

        if ($app = Auth::where('app_key', $app_key)->where('enabled', 1)->get()->toArray()) {
            //$api = 'http://127.0.0.1:8092/api/127.0.0.1'; //不包括 query params
            $api = $_SERVER['APP_URL'] . $_SERVER['API'] . $request->route('ip');
            $request->merge(['ip' => $request->route('ip')]);

            // 计算当前的签名
            $cal_sign = base64_decode(Sign::gen($api, request()->all(), $app[0]['app_secret']));
            // 对签名进行比较
            if ($sign == $cal_sign) {
                $city = new City(storage_path('/app/ipipfree.ipdb'));
                $data = $city->find($request->route('ip'), 'CN');

                return response()->json(['msg' => $data, 'code' => 'ok'])
                ->header('x-ca-error-message', '');
            } else {
                return response()->json(['msg' => 'invalid sign', 'code' => 'err'])
                ->header('x-ca-error-message', 'invalid sign');
            }
        } else {
            return response()->json(['msg' => 'unauthorized request', 'code' => 'err'])
            ->header('x-ca-error-message', 'unauthorized request');
        }
    }

    /**
     * 测试生成签名.
     */
    public function gen(Request $request): JsonResponse
    {
        $auth = Auth::find(1)->toArray();
        if ($ip = $request->get('ip', '')) {
            //计算 nonce
            //$api = 'http://127.0.0.1:8093/api/' . $ip; //不包括 query params
            $api = $_SERVER['APP_URL'] . $_SERVER['API'] . $ip;
            // app key + api + nonce
            $nonce = UUID::v3(md5($auth['app_key'] . $api . time()), Auth::rand_chars());

            $sign = Sign::gen($api, request()->all(), $auth['app_secret']);

            $headers = [
                'x-ca-key'               => $auth['app_key'],
                'x-ca-signature-method'  => 'HmacSHA256',
                'x-ca-signature'         => $sign,
                'x-ca-timestamp'         => time(),
                'x-ca-signature-headers' => 'x-ca-key,x-ca-signature-method,x-ca-signature,x-ca-timestamp,x-ca-signature-headers,x-ca-nonce',
                'x-ca-nonce'             => $nonce,
            ];

            return response()->json(['msg' => $headers, 'code' => 'ok'])->header('x-ca-error-message', '');
        } else {
            return response()->json(['msg' => 'invalid ip', 'code' => 'ok'])->header('x-ca-error-message', 'invalid ip');
        }
    }

    //
}
