<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/8
 * Time: 下午9:30
 */

namespace Swoole;


class Redis
{

    /**
     * Redis异步客户端构造方法，可以设置Redis连接的配置选项
     * Redis constructor.
     * @param $options 配置选项数组，默认为null
     * 超时控制 $options['timeout'] = 1.5;
     * 设置密码 $options['password'] = 'passwd';
     * 设置数据库 $options['database'] = 0;
     */
    public function __construct(array $options = null)
    {
    }

    /**
     * 注册事件回调函数
     * 目前swoole_redis支持2种事件回调函数。on方法必须在connect前被调用
     * @param string $event_name 1.function onClose(swoole_redis $redis); 2.function onMessage(swoole_redis $redis, array $message);
     * @param callable $callback
     */
    public function on(string $event_name, callable $callback){}


    /**
     * 连接到Redis服务器
     * @param string $host
     * @param int $port
     * @param callable $callback   function onConnect(swoole_redis $redis, bool $result);
     */
    public function connect(string $host, int $port, callable $callback)
    {
        
    }

    /**
     * 魔术方法，方法名会映射为Redis指令，参数作为Redis指令的参数
     * @param string $command $command，必须为合法的Redis指令
     * @param array $params $params的最后一个参数必须为可执行的函数，其他参数必须为字符串
     * 订阅/发布指令没有回调函数，不需要在最后一个参数传入callback
     * 使用订阅/发布消息命名，必须设置onMessage事件回调函数
     * 客户端发出了subscribe命令后，只能执行subscribe， psubscribe，unsubscribe，punsubscribe这4条命令
     */
    public function __call(string $command, array $params){}


    /**
     * 关闭Redis连接，不接受任何参数
     */
    public function close(){}


}