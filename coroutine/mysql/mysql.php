<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/5
 * Time: 下午4:56
 */

namespace Swoole\Coroutine\Mysql;

use Swoole\Client as BaseClient;

class Mysql extends BaseClient{
    
    public $serverInfo;
    
    public $sock;

    public $connected;

    public $connect_error;
    
    public $connect_errno;
    
    public $error;
    
    public $errno;
    
    public $affected_rows;

    public $insert_id;

    /**
     * [
        'host' => 'MySQL IP地址',
        'user' => '数据用户',
        'password' => '数据库密码',
        'database' => '数据库名',
        'port'    => 'MySQL端口 默认3306 可选参数',
        'timeout' => '建立连接超时时间',
        'charset' => '字符集',
        'strict_type' => false, //开启严格模式，返回的字段将自动转为数字类型
     ]
     * @param array $serverInfo
     */
    public function connect(array $serverInfo){}

    public function query(string $sql, double $timeout = 0){}

    /**
     * 向MySQL服务器发送SQL预处理请求。prepare必须与execute配合使用。预处理请求成功后，调用execute方法向MySQL服务器发送数据参数。
     * @param string $sql
     * @return bool
     */
    public function prepare(string $sql) : bool{}


    public function execute(array $params) : bool{}


    
}