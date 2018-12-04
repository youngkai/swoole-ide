<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/16
 * Time: 上午11:10
 */

namespace Swoole;


class Timer{

    /**
     * 设置一个间隔时钟定时器，与after定时器不同的是tick定时器会持续触发，直到调用swoole_timer_clear清除
     * @param int $ms 指定时间，单位为毫秒
     * @param callable $callback 时间到期后所执行的函数，必须是可以调用的   回调的两个参数int $timer_id, mixed $params = null
    $timer_id 定时器的ID，可用于swoole_timer_clear清除此定时器   $params 由swoole_timer_tick传入的第三个参数
     * @param mixed $user_param 用户参数, 该参数会被传递到$callback_function中. 如果有多个参数可以使用数组形式. 也可以使用匿名函数的use语法传递参数到回调函数中
     *
     * 定时器仅在当前进程空间内有效
     * 定时器是纯异步实现的，不能与阻塞IO的函数一起使用，否则定时器的执行时间会发生错乱
     * @return int
     */
    public static function tick(int $ms, callable $callback, mixed $user_param){}


    /**
     * 指定的时间后执行函数
     * swoole_timer_after函数是一个一次性定时器，执行完成后就会销毁。此函数与PHP标准库提供的sleep函数不同，after是非阻塞的。而sleep调用后会导致当前的进程进入阻塞，将无法处理新的请求
     * @param int $after_time_ms  指定时间，单位为毫秒，最大不得超过 86400000
     * @param mixed $callback_function  时间到期后所执行的函数，必须是可以调用的
     * @param mixed $user_param
     * @return int 执行成功返回定时器ID，若取消定时器，可调用 swoole_timer_clear
     */
    public static function after(int $after_time_ms, mixed $callback_function, mixed $user_param){}


    /**
     * 使用定时器ID来删除定时器 定时器ID，调用swoole_timer_tick、swoole_timer_after后会返回一个整数的ID
     * @param int $timer_id 不能用于清除其他进程的定时器，只作用于当前进程
     */
    public static function clear(int $timer_id){}
}