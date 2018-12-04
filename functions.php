<?php

// 说明：推荐使用命名空间 + 对象的方式使用 Swoole，而不是采用以下函数

/**
 * 创建一个异步服务器程序
 *
 * @return \Swoole\Server
 */
function swoole_server(){}

/**
 * 创建一个客户端程序
 *
 * @return \Swoole\Client
 *
 * @deprecated
 */
function swoole_client(){}

/**
 * 创建一个进程管理对象
 *
 * @return      \Swoole\Process
 *
 * @deprecated
 */
function swoole_process(){}

/**
 * 设置一个间隔时钟定时器。
 *
 * @param   int         $ms             间隔时间，单位为毫秒。最大不得超过 86400000
 * @param   callback    $callback       回调函数。原型：function callback(int $timer_id, mixed $params = null)
 * @param   mixed       $user_param     用户参数，该参数会被传递到 $callback 中
 * @return  int                         定时器 ID
 *
 * @deprecated
 */
function swoole_timer_tick($ms, $callback, $user_param){}

/**
 * 在指定的时间后执行函数。该函数是一个一次性定时器，执行完后就会销毁。
 *
 * @param   int        $after_time_ms   间隔时间，单位为毫秒。最大不得超过 86400000
 * @param   callback   $callback        回调函数。
 * @return  int                         定时器 ID
 *
 * @deprecated
 */
function swoole_timer_afer($after_time_ms, $callback){}

/**
 * 使用定时器 ID 来删除定时器。
 *
 * @param   int   $timer_id
 * @return  bool
 *
 * @deprecated
 */
function swoole_timer_clear($timer_id){}


function swoole_websocket_server($host, $port){}


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
function swoole_event_add(int $sock, mixed $read_callback, mixed $write_callback = null, int $flags = null){}

/**
 * @param $fd 如果传入$fd在EventLoop中不存在这里会报错
 * @param mixed $read_callback  当$read_callback不为null时，将修改可读事件回调函数为指定的函数
 * @param mixed $write_callback  当$write_callback不为null时，将修改可写事件回调函数为指定的函数
 * @param int $flags  可关闭/开启，可写（SW_EVENT_READ）和可读（SW_EVENT_WRITE）事件的监听
 * @return bool
 */
function swoole_event_set($fd, mixed $read_callback, mixed $write_callback, int $flags){}


/**
 * @param int $sock socket的文件描述符
 * @return bool
 */
function swoole_event_del(int $sock){}


/**
 * 退出事件轮询，此函数仅在Client程序中有效
 */
function swoole_event_exit(){}


/**
 * 脚本开始进行事件轮询
 */
function swoole_event_wait(){}


/**
 * 将stream/sockets资源的数据发送变成异步的，当缓冲区满了或者返回EAGAIN，swoole底层会将数据加入到发送队列，并监听可写。socket可写时swoole底层会自动写入
 * @param $fd
 * @param string $data
 * 不能用于SSL/TLS等有隧道加密的stream/sockets资源
 * 调用之前，必须在将socket加入event_loop，否则会发生错误
 * 发送数据的长度不得超过Socket缓存区尺寸
 */
function swoole_event_write($fd, string $data){}


/**
 * 在下一个事件循环开始时执行函数
 * 前EventLoop的事件循环结束、下一次事件循环启动时响应
 * 时间到期后所执行的函数，必须是可以调用的。回调函数不接受任何参数
 * @param mixed $callback_function
 */
function swoole_event_defer(mixed $callback_function){}


/**
 * 定义事件循环周期执行函数。此函数会在每一轮事件循环结束时调用
 * @param callable $callback 要设置的回调函数，必须为可执行。$callback为null时表示清除cycle函数
 */
function swoole_event_cycle(callable $callback){}


/**
 * 设置异步IO相关的选项
 * @param array $setting
 * thread_num 设置异步文件IO线程的数量
 * aio_mode 设置异步文件IO的操作模式，目前支持SWOOLE_AIO_BASE（使用类似于Node.js的线程池同步阻塞模拟异步）、SWOOLE_AIO_LINUX（Linux Native AIO） 2种模式
 * enable_signalfd 开启和关闭signalfd特性的使用
 * socket_buffer_size 设置SOCKET内存缓存区尺寸
 * socket_dontwait 在内存缓存区已满的情况下禁止底层阻塞等待
 * log_file 设置日志文件路径
 * log_level 设置错误日志等级
 *
 * SWOOLE_AIO_LINUX优点：
 * 所有操作均在一个线程内完成，不需要开线程池
 * 不依赖线程执行IO，所以并发可以非常大
 * 缺点：
 * 只支持DriectIO，无法利用PageCache，所有对文件读写都会直接操作磁盘
 * 写入数据的size必须为512整数倍数
 * 写入数据的offset必须为512整数倍数

 * SWOOLE_AIO_BASE优点：可以利用操作系统PageCache，读写热数据性能非常高，等于读内存
 * 缺点：并发较差，不支持同时读写大量文件，最大并发受限与AIO的线程数量
 *
 */
function swoole_async_set(array $setting){}


/**
 * 异步读文件，使用此函数读取文件是非阻塞的，当读操作完成时会自动回调指定的函数
 * 函数与swoole_async_readfile不同，它是分段读取，可以用于读取超大文件。每次只读$size个字节，不会占用太多内存
 * 在读完后会自动回调$callback函数，回调函数接受2个参数
 * bool callback(string $filename, string $content); 文件名称  读取到的分段内容，如果内容为空，表明文件已读完
 * @param string $filename
 * @param mixed $callback
 * @param int $size
 * @param int $offset
 * $callback函数，可以通过return true/false，来控制是否继续读下一段内容。
return true，继续读取
return false，停止读取并关闭文件
 */
function swoole_async_read(string $filename, mixed $callback, int $size = 8192, int $offset = 0){}


/**
 **********************defer*******************
 * description defer用于资源的释放, 会在协程关闭之前(即协程函数执行完毕时)进行调用, 就算抛出了异常, 已注册的defer也会被执行
 * 2018/12/411:03 AM
 * author yangkai@rsung.com
 *******************************************
 * @param callable $callable
 */
function defer(callable $callable){}


/**
 **********************go*******************
 * description 协程
 * 2018/12/412:18 PM
 * author yangkai@rsung.com
 *******************************************
 * @param callable $callable
 */
function go(callable $callable) {}