<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/12/4
 * Time: 11:07 AM
 */

namespace Swoole;

class Runtime
{

    /**
     **********************enableCoroutine*******************
     * description 在4.1.0版本中，底层增加一个新的特性，可以在运行时动态将基于php_stream实现的扩展、PHP网络客户端代码一键协程化。底层替换了ZendVM Stream的函数指针，所有使用php_stream进行socket操作均变成协程调度的异步IO
     * 2018/12/411:08 AM
     * author yangkai@rsung.com
     *******************************************
     * @param bool $enable
     * @param int $flags
     * $enable：打开或关闭协程
     * $flags：选择要Hook的类型，可以多选，默认为全选。仅在$enable = true时有效
     * SWOOLE_HOOK_SLEEP：睡眠函数
     * SWOOLE_HOOK_FILE：文件操作stream
     * SWOOLE_HOOK_TCP：TCP Socket类型的stream
     * SWOOLE_HOOK_UDP：UDP Socket类型的stream
     * SWOOLE_HOOK_UNIX：Unix Stream Socket类型的stream
     * SWOOLE_HOOK_UDG：Unix Dgram Socket类型的stream
     * SWOOLE_HOOK_SSL：SSL Socket类型的stream
     * SWOOLE_HOOK_TLS：TLS Socket类型的stream
     * SWOOLE_HOOK_ALL：打开所有类型
     */
    public static function enableCoroutine(bool $enable = true, int $flags = SWOOLE_HOOK_ALL) {}

}