<?php
namespace App\Repositories;

use App\Proxy;

class ProxyRepository {
    public static function best()
    {
        $proxy = Proxy::where('is_enabled', 1)->orderBy('amount_used')->first();
        $proxy->amount_used = $proxy->amount_used + 1;
        $proxy->save();

        return $proxy;
    }

    public static function create($arr)
    {
        return Proxy::create([
            'ip' => $arr['ip'],
            'port' => $arr['port'],
            'is_enabled' => 1,
            'amount_used' => 0
        ]);
    }
}