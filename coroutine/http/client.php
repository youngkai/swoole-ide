<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/5
 * Time: 下午4:35
 */

namespace Swoole\Coroutine\Http;

use Swoole\Client as BaseClient;

class Client extends BaseClient{
    
    
    public $errCode;
    
    
    public $body;


    public $statusCode;


    public function get(string $path){}

    public function post(string $path, mixed $data){}

    public function upgrade(string $path){}

    public function push(string $data, int $opcode = WEBSOCKET_OPCODE_TEXT, bool $finish = true){}

    public function recv(){}
    
}