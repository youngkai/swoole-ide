<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/5
 * Time: 下午4:40
 */

namespace Swoole\Coroutine\Http2;

use Swoole\Client as BaseClient;

class Client extends BaseClient{

    public function __construct(string $host, int $port, bool $ssl = false){}

    public function set(array $options){}

    public function connect(){}

    public function send(\Swoole\Coroutine\Http2\Request $request){}

    public function write(int $streamId, mixed $data, bool $end = false){}
    
    public function recv() : \Swoole\Coroutine\Http2\Response{}

    public function close(){}

}