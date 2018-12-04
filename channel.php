<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/12
 * Time: 下午5:48
 */
namespace Swoole;

class Channel{
    
    public function __construct(int $size){}

    public function push(mixed $data){}

    /**
     * @return mixed
     */
    public function pop(){}

    /**
     * @return array
     */
    public function stats(){}
}