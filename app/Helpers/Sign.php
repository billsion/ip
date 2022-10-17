<?php

namespace App\Helpers;

class Sign
{
    /**
     * Undocumented function.
     *
     * @param string $api 接口名称
     * @param array $params query params 参数
     */
    public static function gen(string $api, array $params, string $app_secret): string
    {
        // 对 query params 参数进行 key 键排序，并生成待签名字符串
        if ($params) {
            ksort($params);
            $params      = http_build_query($params);
            $str_to_sign = $api . "?\n" . $params;
        } else {
            $str_to_sign = $api . "\n";
        }

        // 计算签名
        // post
        //$json_body = '';
        //$sign = $api + "\n" + $ksort_params + "\n" + base64_encode(md5($json_body));
        $sign = base64_encode(hash_hmac('sha256', $str_to_sign, $app_secret));

        return $sign;
    }

    public static function compare()
    {
    }
}
