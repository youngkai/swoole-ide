<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/15
 * Time: 下午9:58
 */

namespace Swoole;

class Async{

    /**
     * 异步读取文件内容
     * swoole_async_readfile会将文件内容全部复制到内存，所以不能用于大文件的读取
    如果要读取超大文件，请使用swoole_async_read函数
    swoole_async_readfile最大可读取4M的文件，受限于SW_AIO_MAX_FILESIZE宏
     * @param string $filename
     * @param mixed $callback
     * @return bool
     */
    public static function readFile(string $filename, mixed $callback){}


    /**
     * @param string $filename 文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
     * @param string $fileContent 写入到文件的内容，最大可写入4M
     * @param callable|null $callback 成功后的回调函数，可选
     * @param int $flags 写入的选项，可以使用FILE_APPEND表示追加到文件末尾
     * Linux原生异步IO不支持FILE_APPEND，并且写入的内容长度必须为4096的整数倍，否则底层会自动在末尾填充0
     */
    public static function writeFile(string $filename, string $fileContent, callable $callback = null, int $flags = FILE_APPEND){}

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
    public static function read(string $filename, mixed $callback, int $size = 8192, int $offset = 0){}


    /**
     * 异步写文件，与swoole_async_writefile不同，swoole_async_write是分段写的。不需要一次性将要写的内容放到内存里，所以只占用少量内存。swoole_async_write通过传入的offset参数来确定写入的位置
     * @param string $filename
     * @param string $content
     * @param int $offset 当offset为-1时表示追加写入到文件的末尾
     * @param mixed|NULL $callback
     * Linux原生异步IO不支持追加模式，并且$content的长度和$offset必须为512的整数倍。如果传入错误的字符串长度或者$offset写入会失败，并且错误码为EINVAL
     * @return bool
     */
    public static function write(string $filename, string $content, int $offset = -1, mixed $callback = NULL){}

    /**
     * @param string $host
     * @param callable $callback  $host, $ip
     * 当DNS查询完成时，自动回调指定的callback函数
     * 当DNS查询失败时，比如域名不存在，回调函数传入的$ip为空
     * 关闭DNS缓存
     * swoole_async_set(array(
        'disable_dns_cache' => true,
        ));
     * DNS随机
     * swoole_async_set(array(
        'dns_lookup_random' => true,
        ));
     * 指定DNS服务器
     * swoole_async_set(array(
        'dns_server' => '114.114.114.114',
        ));
     */
    public static function dnsLookup(string $host, callable $callback){}

    /**
     * 异步执行Shell命令。相当于shell_exec函数，执行后底层会fork一个子进程，并执行对应的command命令
     * @param string $command 为执行的终端指令，如ls
     * @param callable $callback 第一个参数为命令执行后的屏幕输出内容$result，第二个参数为进程退出的状态信息$status
     * @return int  执行成功后返回子进程的PID
     * fork创建子进程的操作代价是非常昂贵的，系统无法支撑过大的并发量
     * 使用exec时，请勿使用pcntl_signal或swoole_process::signal注册SIGCHLD函数，执行wait操作，否则在命令回调函数中，状态信息$status将为false
     */
    public static function exec(string $command, callable $callback){}
}