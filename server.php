<?php
namespace Swoole;

/**
 * 异步服务器程序
 * 支持TCP、UDP、UnixSocket 3种协议，支持 IPv4和 IPv6，支持 SSL/TLS 单向双向证书的隧道加密
 * @package Swoole
 */
class Server {

    /**
     * 运行参数
     *
     * @var array
     * @see config.php
     */
    public $setting = [];

    /**
     * 当前服务器主进程的 PID
     *
     * @var int
     */
    public $master_pid;

    /**
     * 当前服务器管理进程的 PID
     *
     * @var int
     */
    public $manager_pid;

    /**
     * 当前 Worker 进程的编号，包括 Task 进程
     *
     * @var int
     */
    public $worker_id;

    /**
     * 当前 Worker 进程的操作系统进程 ID
     *
     * 与 posix_getpid() 的返回值相同
     *
     * @var int
     */
    public $worker_pid;

    /**
     * 是否为任务进程
     *
     * @var bool
     */
    public $taskworker;

    /**
     * 当前所有的连接
     *
     * @var     array
     * @since   1.7.16
     *
     * @see     连接迭代器依赖pcre库（不是PHP的pcre扩展），未安装pcre库无法使用此功能
     *          pcre库的安装方法: http://wiki.swoole.com/wiki/page/312.html
     */
    public $connections;

    /**
     * 创建一个异步服务对象
     *
     * @param   string  $host           主机地址。指定监听的 IP 地址 0.0.0.0 监听全部地址
     * @param   int     $port           端口
     * @param   int     $mode           运行模式。参考常量：SWOOLE_PROCESS、SWOOLE_BASE
     * @param   int     $sockType       Socket 类型。参考常量 SWOOLE_SOCK_ 开头
     */
    public function __construct($host, $port = 0, $mode = SWOOLE_PROCESS, $sockType = SWOOLE_SOCK_TCP){}

    /**
     * 设置运行参数
     *
     * @param   array $setting 参数数组。参见属性 $setting
     * @param  int reactor_num Reactor线程数，reactor_num => 2，通过此参数来调节主进程内事件处理线程的数量，以充分利用多核。默认会启用CPU核数相同的数量 reactor_num建议设置为CPU核数的1-4倍 reactor_num最大不得超过SWOOLE_CPU_NUM * 4
     * @param  int worker_num 业务代码是全异步非阻塞的，这里设置为CPU的1-4倍最合理
     * @param  int max_request 设置worker进程的最大任务数，默认为0，一个worker进程在处理完超过此数值的任务后将自动退出，进程退出后会释放所有内存和资源 纯异步的Server不应当设置max_request 使用Base模式时max_request是无效的
     * @param  int max_conn 服务器程序，最大允许的连接数，如max_connection => 10000, 此参数用来设置Server最大允许维持多少个TCP连接。超过此数量后，新进入的连接将被拒绝
     * @param  task_worker_num 配置Task进程的数量，配置此参数后将会启用task功能。所以Server务必要注册onTask、onFinish2个事件回调函数。如果没有注册，服务器程序将无法启动
     * @param  task_ipc_mode 设置task进程与worker进程之间通信的方式 1, 使用unix socket通信，默认模式 2, 使用消息队列通信 3, 使用消息队列通信，并设置为争抢模式
     * @param  task_max_request 设置task进程的最大任务数。一个task进程在处理完超过此数值的任务后将自动退出。这个参数是为了防止PHP进程内存溢出。如果不希望进程自动退出可以设置为0
     * @param task_tmpdir 设置task的数据临时目录，在swoole_server中，如果投递的数据超过8192字节，将启用临时文件来保存数据。这里的task_tmpdir就是用来设置临时文件保存的位置
     * @param  dispatch_mode 数据包分发策略。可以选择3种类型，默认为2 1，轮循模式，收到会轮循分配给每一个worker进程 2，固定模式，根据连接的文件描述符分配worker。这样可以保证同一个连接发来的数据只会被同一个worker处理 3，抢占模式，主进程会根据Worker的忙闲状态选择投递，只会投递给处于闲置状态的Worker 4，IP分配，根据客户端IP进行取模hash，分配给一个固定的worker进程。可以保证同一个来源IP的连接数据总会被分配到同一个worker进程。算法为 ip2long(ClientIP) % worker_num 5，UID分配，需要用户代码中调用 $serv-> bind() 将一个连接绑定1个uid。然后swoole根据UID的值分配到不同的worker进程。算法为 UID % worker_num，如果需要使用字符串作为UID，可以使用crc32(UID_STRING) 无状态Server可以使用1或3，同步阻塞Server使用3，异步非阻塞Server使用1 有状态使用2、4、5
     * @param dispatch_func 设置dispatch函数，swoole底层了内置了5种dispatch_mode，如果仍然无法满足需求。可以使用编写C++函数或PHP函数，实现dispatch逻辑
     * @param  message_queue_key 设置消息队列的KEY，仅在task_ipc_mode = 2/3时使用。设置的Key仅作为Task任务队列的KEY，此参数的默认值为ftok($php_script_file, 1) task队列在server结束后不会销毁，重新启动程序后，task进程仍然会接着处理队列中的任务。如果不希望程序重新启动后执行旧的Task任务。可以手工删除此消息队列
     * @param daemonize 守护进程化。设置daemonize => 1时，程序将转入后台作为守护进程运行。长时间运行的服务器端程序必须启用此项
     * @param backlog Listen队列长度，如backlog => 128，此参数将决定最多同时有多少个等待accept的连接
     * @param log_file og_file => '/data/log/swoole.log', 指定swoole错误日志文件。在swoole运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕
     * @param log_level 设置swoole_server错误日志打印的等级，范围是0-5。低于log_level设置的日志信息不会抛出
     * @param heartbeat_check_interval 启用心跳检测，此选项表示每隔多久轮循一次，单位为秒。如 heartbeat_check_interval => 60，表示每60秒，遍历所有连接，如果该连接在60秒内，没有向服务器发送任何数据，此连接将被强制关闭
     * @param heartbeat_idle_time 与heartbeat_check_interval配合使用。表示连接最大允许空闲的时间
     * @param open_eof_check 打开EOF检测，此选项将检测客户端连接发来的数据，当数据包结尾是指定的字符串时才会投递给Worker进程。否则会一直拼接数据包，直到超过缓存区或者超时才会中止。当出错时swoole底层会认为是恶意连接，丢弃数据并强制关闭连接
     * @param open_eof_split 启用EOF自动分包。当设置open_eof_check后，底层检测数据是否以特定的字符串结尾来进行数据缓冲。但默认只截取收到数据的末尾部分做对比。这时候可能会产生多条数据合并在一个包内
     * @param package_eof 与 open_eof_check 或者 open_eof_split 配合使用，设置EOF字符串
     * @param open_length_check 打开包长检测特性。包长检测提供了固定包头+包体这种格式协议的解析。启用后，可以保证Worker进程onReceive每次都会收到一个完整的数据包
     * @param package_length_type 长度值的类型，接受一个字符参数，与php的 pack 函数一致。目前Swoole支持10种类型
     * @param package_length_func 设置长度解析函数，支持C++或PHP的2种类型的函数。长度函数必须返回一个整数 返回0，数据不足，需要接收更多数据 返回-1，数据错误，底层会自动关闭连接 返回包长度值（包括包头和包体的总长度），底层会自动将包拼好后返回给回调函数
     * @param package_max_length 设置最大数据包尺寸，单位为字节。开启open_length_check/open_eof_check/open_http_protocol等协议解析后。swoole底层会进行数据包拼接。这时在数据包未收取完整时，所有数据都是保存在内存中的
     * @param open_cpu_affinity 启用CPU亲和性设置。在多核的硬件平台中，启用此特性会将swoole的reactor线程/worker进程绑定到固定的一个核上。可以避免进程/线程的运行时在多个核之间互相切换，提高CPU Cache的命中率
     * @param cpu_affinity_ignore IO密集型程序中，所有网络中断都是用CPU0来处理，如果网络IO很重，CPU0负载过高会导致网络中断无法及时处理，那网络收发包的能力就会下降
     * @param open_tcp_nodelay 启用open_tcp_nodelay，开启后TCP连接发送数据时会关闭Nagle合并算法，立即发往客户端连接。在某些场景下，如http服务器，可以提升响应速度
     * @param tcp_defer_accept 启用tcp_defer_accept特性，可以设置为一个数值，表示当一个TCP连接有数据发送时才触发accept
     * @param ssl_cert_file 设置SSL隧道加密，设置值为一个文件名字符串，制定cert证书和key私钥的路径
     * @param ssl_method 设置OpenSSL隧道加密的算法。Server与Client使用的算法必须一致，否则SSL/TLS握手会失败，连接会被切断。 默认算法为 SWOOLE_SSLv23_METHOD
     * @param ssl_ciphers 启用SSL后，设置ssl_ciphers来改变openssl默认的加密算法。Swoole底层默认使用EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH
     * @param user 设置worker/task子进程的所属用户。服务器如果需要监听1024以下的端口，必须有root权限。但程序运行在root用户下，代码中一旦有漏洞，攻击者就可以以root的方式执行远程指令，风险很大。配置了user项之后，可以让主进程运行在root权限下，子进程运行在普通用户权限下
     * @param group 设置worker/task子进程的进程用户组。与user配置相同，此配置是修改进程所属用户组，提升服务器程序的安全性。
     * @param chroot 重定向Worker进程的文件系统根目录。此设置可以使进程对文件系统的读写与实际的操作系统文件系统隔离。提升安全性。
     * @param pid_file 在Server启动时自动将master进程的PID写入到文件，在Server关闭时自动删除PID文件
     * @param pipe_buffer_size 调整管道通信的内存缓存区长度。Swoole使用Unix Socket实现进程间通信
     * @param buffer_output_size 配置发送输出缓存区内存尺寸
     * @param socket_buffer_size 配置客户端连接的缓存区长度。从1.8.8版本开始swoole底层对于缓存区控制的参数分离成buffer_output_size和socket_buffer_size两项配置 参数buffer_output_size用于设置单次最大发送长度。socket_buffer_size用于设置客户端连接最大允许占用内存数量
     * @param enable_unsafe_event swoole在配置dispatch_mode=1或3后，因为系统无法保证onConnect/onReceive/onClose的顺序，默认关闭了onConnect/onClose事件
     * @param discard_timeout_request discard_timeout_request配置默认为true，表示如果worker进程收到了已关闭连接的数据请求，将自动丢弃。discard_timeout_request如果设置为false，表示无论连接是否关闭Worker进程都会处理数据请求
     * @param enable_reuse_port 设置端口重用，此参数用于优化TCP连接的Accept性能，启用端口重用后多个进程可以同时进行Accept操作
     * @param enable_delay_receive 设置此选项为true后，accept客户端连接后将不会自动加入EventLoop，仅触发onConnect回调。worker进程可以调用$serv->confirm($fd)对连接进行确认，此时才会将fd加入EventLoop开始进行数据收发，也可以调用$serv->close($fd)关闭此连接
     * @param open_http_protocol 启用Http协议处理，Swoole\Http\Server会自动启用此选项。设置为false表示关闭Http协议处理
     * @param open_http2_protocol 启用HTTP2协议解析，需要依赖--enable-http2编译选项。默认为false
     * @param open_websocket_protocol 启用websocket协议处理，Swoole\WebSocket\Server会自动启用此选项。设置为false表示关闭websocket协议处理。设置open_websocket_protocol选项为true后，会自动设置open_http_protocol协议也为true
     * @param open_mqtt_protocol 启用mqtt协议处理，启用后会解析mqtt包头，worker进程onReceive每次会返回一个完整的mqtt数据包
     * @param reload_async 设置异步重启开关。设置为true时，将启用异步安全重启特性，Worker进程会等待异步事件完成后再退出
     * @param tcp_fastopen 开启TCP快速握手特性。此项特性，可以提升TCP短连接的响应速度，在客户端完成握手的第三步，发送SYN包时携带数据
     * @param request_slowlog_file 开启请求慢日志。启用后Manager进程会设置一个时钟信号，定时侦测所有Task和Worker进程，一旦进程阻塞导致请求超过规定的时间，将自动打印进程的PHP函数调用栈
     * @param enable_coroutine
     * @param max_coroutine 设置当前工作进程最大协程数量。超过max_coroutine底层将无法创建新的协程，底层会抛出错误，并直接关闭连接。在Server程序中实际最大可创建协程数量等于 worker_num * max_coroutine
     * @return  bool
     */
    public function set($setting){}

    /**
     * 注册事件
     *
     * @param   string    $event      事件名称 不区分大小写
     * @param   callback  $callback   回调函数
     * @return  bool
     *
     * @see https://wiki.swoole.com/wiki/page/p-event/onStart.html
     * function onStart(swoole_server $server);
     * function onShutdown(swoole_server $server);
     * function onWorkerStart(swoole_server $server, int $worker_id);
     * function onWorkerStop(swoole_server $server, int $worker_id);
     * function onWorkerExit(swoole_server $server, int $worker_id);
     * function onConnect(swoole_server $server, int $fd, int $reactorId);
     * function onReceive(swoole_server $server, int $fd, int $reactor_id, string $data);
     * function onPacket(swoole_server $server, string $data, array $client_info);
     * function onClose(swoole_server $server, int $fd, int $reactorId);
     * function onBufferFull(Swoole\Server $serv, int $fd);
     * function onBufferEmpty(Swoole\Server $serv, int $fd);
     * function onTask(swoole_server $serv, int $task_id, int $src_worker_id, mixed $data);
     * void onFinish(swoole_server $serv, int $task_id, string $data)
     * void onPipeMessage(swoole_server $server, int $src_worker_id, mixed $message);
     * void onWorkerError(swoole_server $serv, int $worker_id, int $worker_pid, int $exit_code, int $signal);
     * void onManagerStart(swoole_server $serv);
     * void onManagerStop(swoole_server $serv);
     */
    public function on($event, $callback){}

    /**
     * 添加一个用户自定义的 Worker 进程
     *
     * @param   \Swoole\Process   $process
     * @return  bool
     *
     * @since   1.7.9+
     */
    public function addProcess($process){}

    /**
     * 增加监听对象
     *
     * @param   string  $host   主机地址
     * @param   int     $port   端口
     * @param   int     $type   Socket 类型。参考常量 SWOOLE_SOCK_开头
     * @return  bool
     */
    public function addListener($host, $port, $type = SWOOLE_SOCK_TCP){}

    /**
     * 增加监听对象。此方法是 addListener 的别名
     *
     * @param   string  $host   主机地址
     * @param   int     $port   端口
     * @param   int     $type   Socket 类型。参考常量 SWOOLE_SOCK_开头
     *
     * @return  bool
     *
     * @since   1.7.9+
     */
    public function listen($host, $port, $type = SWOOLE_SOCK_TCP){}

    /**
     * 启动服务，监听所有的 TCP/UDP 端口
     *
     * @return bool
     */
    public function start(){}

    /**
     * 重启所有 Worker 进程
     *
     * @param   bool $onlyReloadTaskworkrer   是否仅重启 task 进程
     * @return  bool
     *
     * @since   1.7.7   $onlyReloadTaskworkrer 可用
     */
    public function reload($onlyReloadTaskworkrer = false){}

    /**
     * 停止指定的 Worker 进程，并立即触发 onWorkerStop 回调函数
     *
     * @param   int     $workerId      Worker 进程ID。-1 代表当前 Worker 进程
     * @param   bool    $waitEvent     false-表示立即退出，true-等待事件循环为空时再退出
     * @return  bool
     *
     * @since   1.8.2
     * @since   1.9.19  $waitEvent 可用
     */
    public function stop($workerId = -1, $waitEvent = false){}

    /**
     * 关闭服务器
     */
    public function shutdown(){}

    /**
     * 设置一个定时器
     *
     * @param   int         $ms             间隔时间，单位为毫秒。最大不得超过 86400000
     * @param   callback    $callback       回调函数。原型：function callback(int $timerId, mixed $params = null)
     * @param   mixed       $userParam      用户参数，该参数会被传递到 $callback 中
     * @return  int                         定时器 ID
     *
     * @since   1.8.0   在 task 进程中可用
     */
    public function tick($ms, $callback, $userParam){}

    /**
     * 在指定的时间后执行函数
     *
     * @param   int        $afterTimeMs     间隔时间，单位为毫秒。最大不得超过 86400000
     * @param   callback   $callback        回调函数。
     * @return  int                         定时器 ID
     *
     * @since   1.7.7+
     * @since   1.8.0   在 task 进程中可用
     */
    public function after($afterTimeMs, $callback){}

    /**
     * 延后执行一个 PHP 函数。
     *
     * Swoole 底层会在 EventLoop 循环完成后执行此函数。
     * 此函数的目的是为了让一些 PHP 代码延后执行，程序优先处理IO事件
     *
     * @param   callback $callback  回调函数
     * @return  bool
     *
     * @since   1.8.0
     */
    public function defer($callback){}

    /**
     * 删除指定的定时器
     *
     * @param   int   $timerId 定时器 ID
     * @return  bool
     */
    public function clearTimer($timerId){}

    /**
     * 关闭客户端连接
     *
     * @param  int  $fd      客户端连接标识符
     * @param  bool $reset   true-强制关闭连接，丢弃发送队列中的数据
     * @return bool
     *
     * @since  1.8.0
     */
    public function close($fd, $reset = false){}

    /**
     * 向客户端发送数据
     *
     * @param   int     $fd         客户端连接标识符
     * @param   string  $data       要发送的数据。TCP协议最大不得超过 2M，可修改 buffer_output_size  改变允许发送的最大包长度
     * @param   int     $extrData   额外的数据。TCP 发送数据，不需要该参数
     * @return  bool                true-成功，false-失败，通过 $server->getLastError() 方法可以得到错误信息
     */
    public function send($fd, $data, $extrData = 0){}

    /**
     * 向客户端发送文件
     *
     * @param   int       $fd           客户端连接标识符
     * @param   string    $filename     要发送的文件路径。如果文件不存在会返回 false
     * @param   int       $offset       指定文件偏移量。可以从文件的某个位置起发送数据。默认为 0，表示从文件头部开始发送
     * @param   int       $length       指定发送的长度，默认为文件尺寸。
     * @return  bool
     *
     * @since   1.9.17  SSL 客户端连接可用
     * @since   1.9.11  $length 和 $offset 可用
     */
    public function sendFile($fd, $filename, $offset = 0, $length = 0){}

    /**
     * 向任意的客户端 (IP:Port) 发送UDP 数据包
     *
     * @param   string    $ip              IP地址。为 IPV4 字符串，如果 IP 不合法会返回错误
     * @param   int       $port            网络端口号。为 1 - 65535 ，如果端口错误发送会失败
     * @param   string    $data            要发送的数据。可以是文本或者二进制内容
     * @param   int       $serverSocket    使用那个端口发送数据包。服务器可能会同时监听多个 UDP 端口
     * @return  bool
     *
     * @since   1.7.10+
     */
    public function sendTo($ip, $port, $data, $serverSocket = -1){}

    /**
     * 阻塞地向客户端发送数据
     *
     * 仅可用于 SWOOLE_BASE 运行模式下
     *
     * @param   int     $fd         客户端连接标识符
     * @param   string  $sendData   要发送的数据
     * @return  bool
     */
    public function sendWait($fd, $sendData){}

    /**
     * 向任意 worker/task 进程发送消息
     *
     * 在非主进程和管理进程中可调用。收到消息的进程会触发 onPipeMessage 事件
     * 使用 sendMessage 必须注册 onPipeMessage 事件回调函数
     *
     * @param   string  $message        消息内容。没有长度限制，但超过8K时会启动内存临时文件
     * @param   int     $dstWorkerId    目标进程ID。范围是0 ~ (worker_num + task_worker_num - 1)
     * @return  bool
     *
     * @since   1.7.9+
     */
    public function sendMessage($message, $dstWorkerId){}

    /**
     * 检测对应的连接是否存在
     *
     * @param int   $fd 客户端连接标识符
     * @return bool
     *
     * @since  1.7.18+
     */
    public function exist($fd){}

    /**
     * 停止指定客户端的数据接收
     *
     * 调用此函数后会将连接从EventLoop中移除，不再接收客户端数据
     * 此函数不影响发送队列的处理
     * 仅可用于 SWOOLE_BASE 模式
     *
     * @param  int   $fd 客户端连接标识符
     * @return bool
     */
    public function pause($fd){}

    /**
     * 恢复指定客户端的数据接收
     *
     * 调用此函数后会将连接重新加入到EventLoop中，继续接收客户端数据
     * 仅可用于 SWOOLE_BASE 模式
     *
     * @param  int   $fd 客户端连接标识符
     * @return bool
     */
    public function resume($fd){}

    /**
     * 获取连接信息
     *
     * @param   int         $fd             客户端连接标识符
     * @param   int         $extraData      额外的数据
     * @param   bool        $ignoreError    是否忽略错误。true-即使连接关闭也会返回连接的信息
     * @return  array|bool
     */
    public function connection_info($fd, $extraData = 0, $ignoreError = false){}

    /**
     * 获取当前 Server 所有的客户端连接
     *
     * @param   int     $startFd    起始的客户端连接标识符
     * @param   int     $pageSize   每页显示数量。最大不得超过 100
     * @return  array|bool          false-失败 array-客户端连接标识符
     *
     * @since   1.5.8
     */
    public function connection_list($startFd, $pageSize = 10){}

    /**
     * 将连接绑定一个用户定义的 UID，可以设置 dispatch_mode=5 设置以此值进行 hash 固定分配
     *
     * @param   int $fd     客户端连接标识符
     * @param   int $uid    UID
     * @return  bool
     */
    public function bind($fd, $uid){}

    /**
     * 获取当前 Server 的活动 TCP 连接数/启动数据/accpet/close的总次数等信息
     *
     * @return  array
     *              start_time              服务器启动的时间
     *              connection_num          当前连接的数量
     *              accept_count            接受了多少个连接
     *              close_count             关闭的连接数量
     *              request_count           Server收到的请求次数
     *              worker_request_count    当前Worker进程收到的请求次数
     *              tasking_num             当前正在排队的任务数
     *              task_queue_num          消息队列中的Task数量
     *              task_queue_bytes        消息队列的内存占用字节数
     *
     * @since   1.7.5+
     * @since   1.8.5   增加消息队列的统计数据
     */
    public function stats(){}

    /**
     * 投递一个异步的任务到 task 进程池去执行
     *
     * @param   mixed       $data           任务数据
     * @param   int         $dstWorkerId    task 进程ID。指定本参数则任务将被投递到该进程内，-1 表示随机投递
     * @param   callback    $callback       finish 回调函数
     * @return  int|bool                    int- task ID。false 失败
     *
     * @since   1.6.11+     $dstWorkerId
     * @since   1.8.6       $callback
     */
    public function task($data, $dstWorkerId = -1, $callback = null){}

    /**
     * 投递一个异步的任务到 task 进程池去执行。与 task 不同的是 taskwait 是阻塞等待的，直到任务完成或者超时返回。
     *
     * @param   mixed     $data         任务数据
     * @param   float     $timeout      超时时间。单位：秒
     * @param   int       $dstWorkerId  task 进程ID。指定本参数则任务将被投递到该进程内，-1 表示随机投递
     * @return  string|bool
     *
     * @since   1.6.11+   $dstWorkerId
     */
    public function taskWait($data, $timeout = 0.5, $dstWorkerId = -1){}

    /**
     * 并发执行多个 Task
     *
     * @param   array $tasks        任务数组。仅支持关联索引数组
     * @param   float $timeout      超时时间。单位秒
     * @return  array               任务完成或超时，返回结果数组。某个任务执行超时不会影响其他任务，返回的结果数据中将不包含超时的任务
     *
     * @since   1.8.8
     */
    public function taskWaitMulti($tasks, $timeout = 0.5){}

    /**
     * 并发执行 Task 并进行协程调度
     *
     * @param   array   $tasks      任务数组。仅支持关联索引数组
     * @param   float   $timeout    超时时间。单位秒
     * @return  array               任务完成或超时，返回结果数组。某个任务执行超时不会影响其他任务，返回的结果数据中将不包含超时的任务
     *
     * @since   2.0.9
     */
    public function taskCo($tasks, $timeout = 0.5){}

    /**
     * task 进程传递结果数据给 worker 进程
     *
     * 使用此方法，必须注册 onFinish 回调函数
     * finish 是可选的。如果 worker 进程不关心任务执行的结果，不需要调用此函数
     * 在 onTask 回调函数中 return 字符串，等同于调用 finish
     *
     * @param   string  $data
     * @return  bool
     */
    public function finish($data){}

    /**
     * 检测服务器所有连接，并找出已经超过约定时间的连接
     *
     * @param bool $ifCloseConnection 是否自动关闭超时连接
     * @return array
     *
     * @since 1.6.10+
     * @since 1.7.4+  $ifCloseConnection
     */
    public function heartbeat($ifCloseConnection = true){}

    /**
     * 获取最近一次操作错误的错误码
     *
     * @return int
     */
    public function getLastError(){}

    /**
     * 获取底层的 Socket 资源句柄
     *
     * @return resource
     *      1001 连接已经被 Server 端关闭了，出现这个错误一般是代码中已经执行了 $server->close() 关闭了某个连接，
     *           但仍然调用 $server->send()向这个连接发送数据
     *      1002 连接已被 Client 端关闭了，Socket 已关闭无法发送数据到对端
     *      1003 正在执行 close，onClose 回调函数中不得使用 $server->send()
     *      1004 连接已关闭
     *      1005 连接不存在，传入 $fd 可能是错误的
     *      1007 接收到了超时的数据，TCP关闭连接后，可能会有部分数据残留在管道缓存区内，这部分数据会被丢弃
     *      1008 发送缓存区已满无法执行send操作，出现这个错误表示这个连接的对端无法及时收数据导致发送缓存区已塞满
     *      1202 发送的数据超过了 $server->buffer_output_size 设置
     */
    public function getSocket(){}

    /**
     * 设置客户端连接为保护状态，不被心跳线程切断
     *
     * @param   int     $fd     客户端连接标识符
     * @param   bool    $value  状态。true-保护状态 false-表示不保护
     * @return  bool
     */
    public function protect($fd, $value = true){}

    /**
     * 确认连接，与 enable_delay_receive 或 wait_for_bind 配合使用
     *
     * @param   int     $fd 客户端连接标识符
     * @return  bool
     */
    public function confirm($fd){}
}