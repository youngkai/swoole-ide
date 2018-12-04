<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/12
 * Time: 下午4:16
 */

namespace Swoole\Redis;

use Swoole\Server as BaseServer;


class Server extends BaseServer{
    

    public function  setHandler(string $command, callable $callback){}

    public static function format(int $type, mixed $value = null){}

}