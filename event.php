<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/16
 * Time: 上午11:07
 */
namespace Swoole;

class Event{

    /**
     * @param int $sock 文件描述符,包括swoole_client的socket,以及第三方扩展的socket（比如mysql
     * 有四种类型
     * 1.int，就是文件描述符,包括swoole_client的socket,以及第三方扩展的socket（比如mysql）
     * 2.stream资源，就是stream_socket_client/fsockopen 创建的资源
     * 3.sockets资源，就是sockets扩展中 socket_create创建的资源，需要在编译时加入 ./configure --enable-sockets
     * 4.对象，swoole_process 或 swoole_client
     * @param mixed $read_callback
     * @param mixed|null $write_callback
     * @param int|null $flags 事件类型的掩码，可选择关闭/开启可读可写事件，如SWOOLE_EVENT_READ，SWOOLE_EVENT_WRITE，或者SWOOLE_EVENT_READ | SWOOLE_EVENT_WRITE
     *
     * 在可读事件回调函数中必须使用fread、recv等函数读取Socket缓存区中的数据，否则事件会持续触发，如果不希望继续读取必须使用Swoole\Event::del移除事件监听
     * 在可写事件回调函数中，写入socket之后必须调用Swoole\Event::del移除事件监听，否则可写事件会持续触发

     * 执行fread、socekt_recv、socket_read、Swoole\Client::recv返回false，并且错误码为EAGAIN时表示当前Socket接收缓存区内没有任何数据，这时需要加入可读监听等待EventLoop通知
     * 执行fwrite、socket_write、socket_send、Swoole\Client::send操作返回false，并且错误码为EAGAIN时表示当前Socket发送缓存区已满，暂时不能发送数据。需要监听可写事件等待EventLoop通知
     * @return bool
     */
    public static function add(int $sock, mixed $read_callback, mixed $write_callback = null, int $flags = null){}

    /**
     * @param $fd $fd在EventLoop中不存在这里会报错
     * @param mixed $read_callback  当$read_callback不为null时，将修改可读事件回调函数为指定的函数
     * @param mixed $write_callback  当$write_callback不为null时，将修改可写事件回调函数为指定的函数
     * @param int $flags  可关闭/开启，可写（SW_EVENT_READ）和可读（SW_EVENT_WRITE）事件的监听
     * @return bool
     */
    public static function set($fd, mixed $read_callback, mixed $write_callback, int $flags){}


    /**
     * @param int $sock socket的文件描述符
     * @return bool
     */
    public static function del(int $sock){}


    /**
     * 退出事件轮询，此函数仅在Client程序中有效
     */
    public static function exit(){}


    /**
     * 脚本开始进行事件轮询
     */
    public static function wait(){}


    /**
     * 将stream/sockets资源的数据发送变成异步的，当缓冲区满了或者返回EAGAIN，swoole底层会将数据加入到发送队列，并监听可写。socket可写时swoole底层会自动写入
     * @param $fd
     * @param string $data
     * 不能用于SSL/TLS等有隧道加密的stream/sockets资源
     * 调用之前，必须在将socket加入event_loop，否则会发生错误
     * 发送数据的长度不得超过Socket缓存区尺寸
     */
    public static function write($fd, string $data){}


    /**
     * 在下一个事件循环开始时执行函数
     * 前EventLoop的事件循环结束、下一次事件循环启动时响应
     * 时间到期后所执行的函数，必须是可以调用的。回调函数不接受任何参数
     * @param mixed $callback_function
     */
    public static function defer(mixed $callback_function){}


    /**
     * 定义事件循环周期执行函数。此函数会在每一轮事件循环结束时调用
     * @param callable $callback 要设置的回调函数，必须为可执行。$callback为null时表示清除cycle函数
     */
    public static function cycle(callable $callback){}


    /**
     **********************dispatch*******************
     * description 仅执行一次reactor->wait操作，在Linux平台下相当手工调用一次epoll_wait。与swoole_event_wait不同的是，swoole_event_wait在底层内部维持了循环
     * 2018/12/411:43 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public function dispatch(){}
}