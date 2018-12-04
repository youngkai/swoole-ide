<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/1
 * Time: 下午1:14
 */
namespace Swoole\Coroutine;

use Swoole\Client as BaseClient;

class Client extends BaseClient{

    public function connect(string $host, int $port, float $timeout = 0.1){}

    public function send(string $data){}

    public function recv() : string{}

    public function close() : bool{}

}