<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/12/4
 * Time: 11:12 AM
 */

namespace Swoole;

class Atomic
{
    public function __construct(int $init_value = 0){}

    /**
     **********************add*******************
     * description 增加计数
     * 2018/12/411:21 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $add_value
     */
    public function add(int $add_value = 1){}

    /**
     **********************sub*******************
     * description 减少计数
     * 2018/12/411:21 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $sub_value
     */
    public function sub(int $sub_value = 1){}

    /**
     **********************get*******************
     * description 获取当前计数的值
     * 2018/12/411:20 AM
     * author yangkai@rsung.com
     *******************************************
     * @return int
     */
    public function get(){}

    /**
     **********************set*******************
     * description 将当前值设置为指定的数字
     * 2018/12/411:21 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $value
     */
    public function set(int $value){}

    /**
     **********************cmpset*******************
     * description 如果当前数值等于参数1，则将当前数值设置为参数2
     * 2018/12/411:22 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $cmp_value
     * @param int $set_value
     * 如果当前数值等于$cmp_value返回true，并将当前数值设置为$set_value
     * 如果不等于返回false
     * $cmp_value，$set_value 必须为小于42亿的整数
     */
    public function cmpset(int $cmp_value, int $set_value){}

    /**
     **********************wait*******************
     * description 当原子计数的值为0时程序进入等待状态。另外一个进程调用wakeup可以再次唤醒程序。底层基于Linux Futex实现，使用此特性，可以仅用4字节内存实现一个等待、通知、锁的功能
     * 2018/12/411:23 AM
     * author yangkai@rsung.com
     *******************************************
     * @param float $timeout
     * @return bool
     */
    public function wait(float $timeout = -1){}

    /**
     **********************wakeup*******************
     * description 唤醒处于wait状态的其他进程
     * 2018/12/411:23 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $n
     */
    public function wakeup(int $n = 1){}
}