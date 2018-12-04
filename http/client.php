<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/16
 * Time: 下午3:33
 */

namespace Swoole\Http;

use Swoole\Client as BaseClient;


/**
 * Class Client
 * @package Swoole\Http
 *
 *
 * 某些情况下服务器端没有正常返回数据，底层会将$statusCode标记为负数。

-1：连接超时，服务器未监听端口或网络丢失，可以读取$errCode获取具体的网络错误码
-2：请求超时，服务器未在规定的timeout时间内返回response
-3：客户端请求发出后，服务器强制切断连接
 *
 *
 */
class Client extends BaseClient{

    /**
     * @var 请求响应后服务器端返回的内容
     */
    public $body;

    /**
     * @var 服务器端返回的Http状态码，如404、200、500等
     */
    public $statusCode;

    /**
     * @var 服务器端返回的原始COOKIE信息，包括了domain和path项
     */
    public $set_cookie_headers;

    /**
     * @var Http请求头
     */
    public $headers;

    /**
     * @var Http Cookie
     */
    public $cookies;

    /**
     * Client constructor.
     * @param string $ip目标服务器的IP地址，可使用swoole_async_dns_lookup查询域名对应的IP地址
     * @param int $ 目标服务器的端口，一般http为80，https为443
     * @param bool $ssl 是否启用SSL/TLS隧道加密，如果目标服务器是https必须设置$ssl参数为true
     */
    public function __construct(string $ip, int $port, bool $ssl = false){}


    /**
     * 设置客户端参数，此方法与Swoole\Client->set接收的参数完全一致，可参考 Swoole\Client->set 方法的文档。
     * @param array $arr
     * 1.超时控制 $http->set(['timeout' => 3.0]);
     * 2.keep_alive $http->set(['keep_alive' => false]);
     * 3.websocket_mask WebSocket客户端启用或关闭掩码。默认为关闭。启用后会对WebSocket客户端发送的数据使用掩码进行数据转换 $http->set(['websocket_mask' => true]);
     */
    public function set(array $arr){}


    /**
     * 设置Http请求方法
     * @param string $method 必须为符合Http标准的方法名称，如果$method设置错误可能会被Http服务器拒绝请求
     * setMethod仅在当前请求有效，发送请求后会立刻清除method设置
     */
    public function setMethod(string $method){}


    /**
     * 设置Http请求头
     * $headers必须为键值对应的数组，底层会自动映射为$key: $value格式的Http标准头格式
     * setHeaders设置的Http头在swoole_http_client对象存活期间的每次请求永久有效
     * 重新调用setHeaders会覆盖上一次的设置
     * @param array $headers
     */
    public function setHeaders(array $headers){}


    /**
     * 设置Cookie
     * $cookies 设置COOKIE，必须为键值对应数组
     * 设置COOKIE后在客户端对象存活期间会持续保存
     * 服务器端主动设置的COOKIE会合并到cookies数组中，可读取$client->cookies属性获得当前Http客户端的COOKIE信息
     * @param array $cookies
     */
    public function setCookies(array $cookies){}


    /**
     * 设置Http请求的包体
     * @param string $data $data 为字符串格式
     * 设置$data后并且未设置$method，底层会自动设置为POST
     * 未设置Http请求包体并且未设置$method，底层会自动设置为GET
     */
    public function setData(string $data){}

    /**
     * 添加POST文件
     * @param string $path 文件的路径，必选参数，不能为空文件或者不存在的文件
     * @param string $name 表单的名称，必选参数，FILES参数中的key
     * @param string|null $filename  文件名称，可选参数，默认为basename($path)
     * @param string|null $mimeType 文件的MIME格式，可选参数，底层会根据文件的扩展名自动推断
     * @param int $offset 上传文件的偏移量，可以指定从文件的中间部分开始传输数据。此特性可用于支持断点续传。
     * @param int $length 发送数据的尺寸，默认为整个文件的尺寸
     * 使用addFile会自动将POST的Content-Type将变更为form-data。addFile底层基于sendfile，可支持异步发送超大文件
     */
    public function addFile(string $path, string $name, string $filename = null, string $mimeType = null, int $offset = 0, int $length){}

    /**
     * 发起GET请求
     * @param string $path  设置URL路径，如/index.html，注意这里不能传入http://domain
     * @param callable $callback 调用成功或失败后回调此函数
     * 默认使用GET方法，可使用setMethod设置新的请求方法
     * Http响应内容会在内存中进行数据拼接。因此如果响应体很大可能会占用大量内存
     */
    public function get(string $path, callable $callback){}


    /**
     * 发起POST请求
     * @param string $path 设置URL路径，如/index.html，注意这里不能传入http://domain
     * @param mixed $data 请求的包体数据，如果$data为数组底层自动会打包为x-www-form-urlencoded格式的POST内容，并设置Content-Type为application/x-www-form-urlencoded
     * @param callable $callback 调用成功或失败后回调此函数
     */
    public function post(string $path, mixed $data, callable $callback){}


    /**
     * 发起WebSocket握手请求，并将连接升级为WebSocket
     * @param string $path URL路径
     * @param callable $callback 握手成功或失败后回调此函数
     * 使用Upgrade方法必须设置onMessage回调函数
     * function onMessage(swoole_http_client $client, swoole_websocket_frame $frame);
     */
    public function upgrade(string $path, callable $callback){}

    /**
     * 向WebSocket服务器发送数据
     * @param string $data 要发送的数据内容，默认为UTF-8文本格式，如果为其他格式编码或二进制数据，请使用WEBSOCKET_OPCODE_BINARY
     * @param int $opcode 操作类型，默认为WEBSOCKET_OPCODE_TEXT表示发送文本
     * @param bool $finish  必须为合法的WebSocket OPCODE，否则会返回失败，并打印错误信息opcode max 10
     * @return bool
     */
    public function push(string $data, int $opcode = WEBSOCKET_OPCODE_TEXT, bool $finish = true){}


    /**
     * 更底层的Http请求方法，需要代码中调用setMethod和setData等接口设置请求的方法和数据
     * @param string $path
     * @param callable $callback
     */
    public function execute(string $path, callable $callback){}


    /**
     * 通过Http下载文件。download与get方法的不同是download收到数据后会写入到磁盘，而不是在内存中对Http Body进行拼接。因此download仅使用小量内存，就可以完成超大文件的下载
     * @param string $path URL路径
     * @param string $filename 指定下载内容写入的文件路径，会自动写入到downloadFile属性
     * @param callable $callback 下载成功后的回调函数
     * @param int $offset 指定写入文件的偏移量，此选项可用于支持断点续传，可配合Http头Range:bytes=$offset-实现 为0时若文件已存在，底层会自动清空此文件
     * @return bool
     */
    public function download(string $path, string $filename, callable $callback, int $offset = 0){}


    /**
     * 关闭连接
     */
    public function close(){}
}