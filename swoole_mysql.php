<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/13
 * Time: 下午5:09
 */

class swoole_mysql{

    public $connect_errno;

    public $connect_error;

    public function connect($config, callable $callback){}

    public function on($event, callable $callback){}

    public function begin(callable $callback){}

    public function query($sql, callable $callback){}

    public function rollback(callable $callback){}
}