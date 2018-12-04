<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/12/4
 * Time: 11:24 AM
 */

namespace Swoole;


class Buffer
{

    /**
     * Buffer constructor.
     * 创建一个内存对象
     * 参数$size指定了缓冲区内存的初始尺寸。当申请的内存容量不够时swoole底层会自动扩容。
     * @param int $size
     */
    public function __construct(int $size = 128){}

    /**
     **********************append*******************
     * description 将一个字符串数据追加到缓存区末尾
     * 2018/12/411:26 AM
     * author yangkai@rsung.com
     *******************************************
     * @param string $data
     */
    public function append(string $data){}

    /**
     **********************substr*******************
     * description 从缓冲区中取出内容
     * 2018/12/411:26 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $offset
     * @param int $length
     * @param bool $remove
     */
    public function substr(int $offset, int $length = -1, bool $remove = false){}

    /**
     **********************clear*******************
     * description 清理缓存区数据
     * 2018/12/411:26 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public function clear(){}

    /**
     **********************expand*******************
     * description 为缓存区扩容
     * 2018/12/411:26 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $new_size
     */
    public function expand(int $new_size){}

    /**
     **********************write*******************
     * description 向缓存区的任意内存位置写数据。read/write函数可以直接读写内存。所以使用务必要谨慎，否则可能会破坏现有数据
     * 2018/12/411:27 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $offset
     * @param string $data
     */
    public function write(int $offset, string $data){}

    /**
     **********************read*******************
     * description 读取缓存区任意位置的内存
     * 2018/12/411:27 AM
     * author yangkai@rsung.com
     *******************************************
     * @param int $offset
     * @param int $length
     */
    public function read(int $offset, int $length){}

    /**
     **********************recycle*******************
     * description 回收缓冲中已经废弃的内存
     * 此方法能够在不清空缓冲区和使用 swoole_buffer->clear() 的情况下，回收通过 swoole_buffer->substr() 移除但仍存在的部分内存空间。
     * 2018/12/411:27 AM
     * author yangkai@rsung.com
     *******************************************
     */
    public function recycle(){}



}