<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/5
 * Time: 下午6:01
 */
namespace Swoole;


class Coroutine{

    /**
     **********************getuid*******************
     * description 获取当前协程的唯一ID
     * 2018/12/410:25 AM
     * author yangkai@rsung.com
     *******************************************
     * @return int
     */
    public static function getuid() {}

    /**
     * max_coroutine
    设置最大协程数，超过限制后底层将无法创建新的协程。
    stack_size
    设置单个协程初始栈的内存尺寸，默认为8192
     */
    public static function set($setting = []){}

    /**
     **********************create*******************
     * description 创建一个新的协程，并立即执行
     * 2018/12/410:19 AM
     * author yangkai@rsung.com
     *******************************************
     * @param callable $function
     * @return int | bool
     */
    public static function create(callable $function){}

    /**
     **********************yield*******************
     * description 让出当前协程的执行权
     * 2018/12/410:22 AM
     * author yangkai@rsung.com
     * 必须与Coroutine::resume()方法成对使用。该协程yield以后，必须由其他外部协程resume，否则将会造成协程泄漏，被挂起的协程永远不会执行
     *******************************************
     */
    public static function yield(){}

    /**
     **********************resume*******************
     * description 恢复某个协程，使其继续运行
     * 2018/12/410:22 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $coroutineId
     * 参数$coroutineId为要恢复的协程ID，在协程内可以使用Coroutine::getUid获取到协程的ID
     * 当前协程处于挂起状态时，另外的协程中可以使用resume再次唤醒当前协程
     */
    public static function resume(int $coroutineId){}

    /**
     **********************suspend*******************
     * description 挂起当前协程。此函数是Coroutine::yield()方法的别名。
     * 2018/12/410:25 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public static function suspend(){}

    /**
     **********************fread*******************
     * description 协程方式读取文件
     * 2018/12/410:26 AM
     * author yangkai@rsung.com
     *******************************************
     * @param resource $handle
     * @param int $length
     * @return mixed
     */
    public static function fread(resource $handle, int $length = 0) {}

    /**
     **********************fgets*******************
     * description 协程方式按行读取文件内容
     * 2018/12/410:27 AM
     * author yangkai@rsung.com
     *******************************************
     * @param resource $handle
     * Co::fgets底层使用了php_stream缓存区，默认大小为8192字节，可使用stream_set_chunk_size设置缓存区尺寸
     */
    public static function fgets(resource $handle){}


    /**
     **********************fwrite*******************
     * description 协程方式向文件写入数据
     * 2018/12/410:28 AM
     * author yangkai@rsung.com
     *******************************************
     * @param resource $handle
     * @param string $data
     * @param int $length
     */
    public static function fwrite(resource $handle, string $data, int $length = 0){}

    /**
     **********************sleep*******************
     * description 进入等待状态。相当于PHP的sleep函数，不同的是Coroutine::sleep是协程调度器实现的，底层会yield当前协程，让出时间片，并添加一个异步定时器，当超时时间到达时重新resume当前协程，恢复运行。使用sleep接口可以方便地实现超时等待功能
     * 2018/12/410:29 AM
     * author yangkai@rsung.com
     *******************************************
     * @param float $seconds
     */
    public static function sleep(float $seconds){}


    /**
     **********************gethostbyname*******************
     * description 将域名解析为IP，基于同步的线程池模拟实现。底层自动进行协程调度
     * 2018/12/410:54 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $domain
     * @param int $family
     */
    public static function gethostbyname(string $domain, int $family = AF_INET){}

    /**
     **********************getaddrinfo*******************
     * description 进行DNS解析，查询域名对应的IP地址，与gethostbyname不同，getaddrinfo支持更多参数设置，而且会返回多个IP结果。
     * 2018/12/410:55 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public static function getaddrinfo(string $domain, int $family = AF_INET, int $socktype = SOCK_STREAM, int $protocol = IPPROTO_TCP, string $service = null){}

    /**
     **********************exec*******************
     * description 执行一条shell指令。底层自动进行协程调度
     * 2018/12/410:56 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $cmd
     * @return mixed
     */
    public static function exec(string $cmd){}


    /**
     **********************readFile*******************
     * description 协程方式读取文件
     * 2018/12/410:57 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $filename
     */
    public static function readFile(string $filename) {}

    /**
     **********************writeFile*******************
     * description 协程方式写入文件
     * 2018/12/410:58 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $filename
     * @param string $fileContent
     * @param int $flags
     */
    public static function writeFile(string $filename, string $fileContent, int $flags){}


    /**
     **********************stats*******************
     * description 获取协程状态
     * 2018/12/410:59 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public static function stats(){}


    /**
     **********************statvfs*******************
     * description 获取文件系统信息
     * 2018/12/410:59 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $path
     */
    public static function statvfs(string $path){}


    /**
     **********************getBackTrace*******************
     * description 获取协程函数调用栈
     * 2018/12/411:00 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $cid
     * @param int $options
     * @param int $limit
     */
    public static function getBackTrace(int $cid=0, int $options=DEBUG_BACKTRACE_PROVIDE_OBJECT, int $limit=0){}


    /**
     **********************listCoroutines*******************
     * description 遍历当前进程内的所有协程
     * 2018/12/411:01 AM
     * author yangkai@rsung.com
     *******************************************
     * @return \Iterator
     */
    public static function listCoroutines(){}


    /**
     **********************defer*******************
     * description defer用于资源的释放, 会在协程关闭之前(即协程函数执行完毕时)进行调用, 就算抛出了异常, 已注册的defer也会被执行
     * 2018/12/411:04 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public static function defer() {}
}