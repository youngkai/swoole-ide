<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/5
 * Time: 下午9:22
 */

namespace Swoole\Coroutine;

class Channel{


    public function __construct(int $capacity = 0){}

    public function push(mixed $data) : bool{}

    public function pop() : mixed{}

    public function stats() : array{}

    public function close(){}

    public static function select(array &$read, array &$write, float $timeout = 0){}

}