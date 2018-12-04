<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/16
 * Time: 下午5:22
 */

namespace Swoole\Http2;

use Swoole\Client as BaseClient;

class Client extends BaseClient{


    /**
     * 构造方法，与swoole_http_client的构造方法参数完全一致，共接受3个参数
     * Client constructor.
     * @param int $host $host 服务器的地址，如果未设置host头，将自动使用$host参数作为默认的host头
     * @param int $port $port 端口号，SSL一般为443，非SSL一般为80
     * @param bool $ssl $ssl 是否启用SSL加密，需要依赖openssl
     */
    public function __construct($host, $port, $ssl = false){

    }

    /**
     * 发起GET请求
     * @param string $path 设置URL路径，如/index.html，注意这里不能传入http://domain
     * @param callable $callback 调用成功或失败后回调此函数
     * Http响应内容会在内存中进行数据拼接。因此如果响应体很大可能会占用大量内存
     * 与Http1.1客户端事件回调函数不同，Http2.0回调函数中的参数为Swoole\Http2\Response对象。而不是Client本身。可使用use语法将Client对象传递给匿名函数
     * function callback(Swoole\Http2\Response $resp)
        {
        var_dump($resp->cookie);
        var_dump($resp->header);
        var_dump($resp->server);
        var_dump($resp->body);
        var_dump($resp->statusCode);
        }
     */
    public function get(string $path, callable $callback){}

    /**
     * 发起POST请求
     * @param string $path 设置URL路径，如/index.html，注意这里不能传入http://domain
     * @param mixed $data 请求的包体数据，如果$data为数组底层自动会打包为x-www-form-urlencoded格式的POST内容，并设置Content-Type为application/x-www-form-urlencoded
     * @param callable $callback 调用成功或失败后回调此函数
     */
    public function post(string $path, mixed $data, callable $callback){

    }


    /**
     * 设置Http请求头
     * @param array $headers
     * $headers必须为键值对应的数组，底层会自动映射为$key: $value格式的Http标准头格式
     * setHeaders设置的Http头在swoole_http2_client对象存活期间的每次请求永久有效
     * 重新调用setHeaders会覆盖上一次的设置
     */
    public function setHeaders(array $headers){}

    /**
     * 设置Cookie
     * @param array $cookies
     * $cookies 设置COOKIE，必须为键值对应数组
     * 设置COOKIE后在客户端对象存活期间会持续保存
     * 服务器端主动设置的COOKIE会合并到cookies数组中，可读取$client->cookies属性获得当前Http2客户端的COOKIE信息
     * 重新调用setCookies方法会覆盖已有COOKIE
     */
    public function setCookies(array $cookies){}
}