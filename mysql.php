<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/16
 * Time: 上午11:21
 */

namespace Swoole;


class Mysql{

    /**
     * Mysql constructor.
     * @param $confog
     * $server = array(
        'host' => '192.168.56.102',
        'port' => 3306,
        'user' => 'test',
        'password' => 'test',
        'database' => 'test',
        'charset' => 'utf8', //指定字符集
        'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
        );
     */
    public function __construct(array $confog = [])
    {

    }

    /**
     * @param string $event 事件  1.Close
     * @param callable $callback  回调参数$db
     */
    public function on(string $event, callable $callback){}

    /**
     * 异步连接到MySQL服务器
     * @param array $serverConfig
     * $server = array(
        'host' => '192.168.56.102',
        'user' => 'test',
        'password' => 'test',
        'database' => 'test',
        'charset' => 'utf8',
        );
     * @param callable $callback  回调onConnect(swoole_mysql $db, bool $result);
     *
     *  $db 为swoole_mysql对象
        $result 连接是否成功，只有为true时才可以执行query查询
        $result 为false，可以通过connect_errno和connect_error得到失败的错误码和错误信息
     */
    public function connect(array $serverConfig, callable $callback){}

    /**
     * 转义SQL语句中的特殊字符，避免SQL注入攻击。底层基于mysqlnd提供的函数实现，需要依赖PHP的mysqlnd扩展
     * 必须在connect完成后才能使用
     * @param string $str
     */
    public function escape(string $str){}

    /**
     * 执行SQL查询
     * 每个MySQLi连接只能同时执行一条SQL，必须等待返回结果后才能执行下一条SQL
     * @param $sql
     * @param callable $callback  onSQLReady(swoole_mysql $link, mixed $result);
     * 执行失败，$result为false，读取$link对象的error属性获得错误信息，errno属性获得错误码
     * 执行成功，SQL为非查询语句，$result为true，读取$link对象的affected_rows属性获得影响的行数，insert_id属性获得Insert操作的自增ID
     * 执行成功，SQL为查询语句，$result为结果数组
     */
    public function query($sql, callable $callback){}


    /**
     * 启动事务
     * 启动一个MySQL事务，事务启动成功会回调指定的函数
     * 与commit和rollback结合实现MySQL事务处理
     * 同一个MySQL连接对象，同一时间只能启动一个事务
     * 必须等到上一个事务commit或rollback才能继续启动新事务
     * 否则底层会抛出Swoole\MySQL\Exception异常，异常code为21
     * @param callable $callback
     */
    public function begin(callable $callback){}


    /**
     * 提交事务
     * 提交事务，当服务器返回响应时回调此函数
     * 必须先调用begin启动事务才能调用commit否则底层会抛出Swoole\MySQL\Exception异常
     * 异常code为22
     * @param callable $callback
     */
    public function commit(callable $callback){}

    /**
     * 回滚事务
     * 必须先调用begin启动事务才能调用rollback否则底层会抛出Swoole\MySQL\Exception异常
     * @param callable $callback
     */
    public function rollback(callable $callback){}


    /**
     * 关闭连接
     */
    public function close(){}
}