<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/12/4
 * Time: 11:28 AM
 */

namespace Swoole;

class Lock
{

    /**
     * swoole_lock constructor.
     * $type为锁的类型
     * $lockfile，当类型为SWOOLE_FILELOCK时必须传入，指定文件锁的路径
     */
    public function __construct(int $type, array $lockfile){}

    //加锁操作。如果有其他进程持有锁，那这里将进入阻塞，直到持有锁的进程unlock。
    //return boolean
    public function lock(){}

    //与lock方法不同的是，trylock()不会阻塞，它会立即返回
    //加锁成功返回true，此时可以修改共享变量。
    //加锁失败返回false，表示有其他进程持有锁。
    public function trylock(){}

    public function unlock(){}

    //在持有读锁的过程中，其他进程依然可以获得读锁，可以继续发生读操作
    //但不能$lock->lock()或$lock->trylock()，这两个方法是获取独占锁，在独占锁加锁时，其他进程无法再进行任何加锁操作，包括读锁
    //当另外一个进程获得了独占锁(调用$lock->lock/$lock->trylock)时，$lock->lock_read()会发生阻塞，直到持有独占锁的进程释放锁
    //只读加锁。lock_read方法表示仅锁定读
    public function lock_read(){}


    //加锁。此方法与lock_read相同，但是非阻塞的。
    public function trylock_read(){}

    //加锁操作，作用于swoole_lock->lock一致，但lockwait可以设置超时时间
    public function lockwait(float $timeout = 1.0) {}



}