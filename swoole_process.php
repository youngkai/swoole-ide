<?php
/**
 * Created by PhpStorm.
 * User: youngk
 * Date: 2018/3/13
 * Time: 下午6:20
 */


/**
 * SIGCHLD，在一个进程终止或者停止时，将SIGCHLD信号发送给其父进程，按系统默认将忽略此信号，如果父进程希望被告知其子系统的这种状态，则应捕捉此信号
 * 信号在实际研发中的实用价值高，在使用中有两个函数可以产生这个信号，它们是alarm和setitimer，它们的区别是alarm相当于单次定时器，setitimer相当于循环定时器
 * Class swoole_process
 */
class swoole_process{

    public $id;

    public $pid;

    public $pipe;

    /**
     * 回调函数的名称
     */
    public $callback;

    /**
     * swoole_process constructor.
     * @param callable $function  子进程创建成功后要执行的函数，底层会自动将函数保存到对象的callback属性上。如果希望更改执行的函数，可赋值新的函数到对象的callback属性
     * @param bool $redirect_stdin_stdout 重定向子进程的标准输入和输出。启用此选项后，在子进程内输出内容将不是打印屏幕，而是写入到主进程管道。读取键盘输入将变为从管道中读取数据。默认为阻塞读取
     * @param bool $create_pipe 是否创建管道，启用$redirect_stdin_stdout后，此选项将忽略用户参数，强制为true。如果子进程内没有进程间通信，可以设置为 false
     */
    public function __construct(callable $function, $redirect_stdin_stdout = false, $create_pipe = true){}


    /**
     * @return mixed
     */
    public function start(){}

    /**
     * 修改进程名称。此函数是swoole_set_process_name的别名
     * 在执行exec后，进程名称会被新的程序重新设置
     * @param string $name
     */
    public function name(string $name){}

    /**
     * 执行一个外部程序，此函数是exec系统调用的封装
     * @param string $execfile 指定可执行文件的绝对路径，如 "/usr/bin/python"
     * @param array $args 是一个数组，是exec的参数列表，如 array('test.py', 123)，相当与python test.py 123
     * @return bool
     */
    public function exec(string $execfile, array $args){}

    /**
     * 向管道内写入数据
     * @param string $data $data的长度在Linux系统下最大不超过8K，MacOS/FreeBSD下最大不超过2K
     * 在子进程内调用write，父进程可以调用read接收此数据
     * 在父进程内调用write，子进程可以调用read接收此数据
     */
    public function write(string $data){}

    /**
     * 从管道中读取数据
     * @param int $buffer_size 是缓冲区的大小，默认为8192，最大不超过64K
     * 管道类型为DGRAM数据报时，read可以读取完整的一个数据包
     * 管道类型为STREAM时，read是流式的，需要自行处理包完整性问题
     * 读取成功返回二进制数据字符串，读取失败返回false
     * @return mixed
     */
    public function read(int $buffer_size=8192){}

    /**
     * 设置管道读写操作的超时时间
     * @param float $timeout 单位为秒，支持浮点型，如1.5表示1s+500ms
     * @return bool
     */
    public function setTimeout(double $timeout){}

    /**
     * 启用消息队列作为进程间通信
     * @param int $msgkey 是消息队列的key，默认会使用ftok(__FILE__, 1)作为KEY
     * @param int $mode 通信模式，默认为2，表示争抢模式，所有创建的子进程都会从队列中取数据
     * @return bool
     */
    public function useQueue(int $msgkey = 0, int $mode = 2){}

    /**
     * 查看消息队列状态
     * @return array
     */
    public function statQueue(){}

    /**
     * 删除队列
     * 如果程序中只调用了useQueue方法，未调用freeQueue在程序结束时并不会清除数据。重新运行程序时可以继续读取上次运行时留下的数据
     */
    public function freeQueue(){}

    /**
     * 投递数据到消息队列中
     * 默认模式下（阻塞模式），如果队列已满，push方法会阻塞等待
     * 非阻塞模式下，如果队列已满，push方法会立即返回false
     * @param string $data 要投递的数据，长度受限与操作系统内核参数的限制。默认为8192，最大不超过65536
     * @return bool
     */
    public function push(string $data){}

    /**
     * 从队列中提取数据
     * @param int $maxsize 表示获取数据的最大尺寸，默认为8192
     * @return string
     */
    public function pop(int $maxsize = 8192){}

    /**
     * 用于关闭创建的好的管道
     * @param int $which 指定关闭哪一个管道，默认为0表示同时关闭读和写，1：关闭写，2关闭读
     * @return bool
     */
    public function close(int $which = 0){}

    /**
     * 退出子进程
     * @param int $status 是退出进程的状态码，如果为0表示正常结束，会继续执行PHP的shutdown_function，其他扩展的清理工作。

    如果$status不为0，表示异常退出，会立即终止进程。不再执行PHP的shutdown_function，其他扩展的清理工作。

    在父进程中，执行swoole_process::wait可以得到子进程退出的事件和状态码
     * @return int
     */
    public function exit(int $status=0){}

    /**
     * 向指定pid进程发送信号
     * 子进程退出后，父进程务必要执行swoole_process::wait进行回收，否则这个子进程就会变为僵尸进程。会浪费操作系统的进程资源
     * 父进程可以设置监听SIGCHLD信号，收到信号后执行swoole_process::wait回收退出的子进程
     * @param $pid 默认的信号为SIGTERM，表示终止进程
     * @param int $signo $signo=0，可以检测进程是否存在，不会发送信号
     * @return bool
     */
    public static function kill($pid, $signo = SIGTERM){}

    /**
     * 回收结束运行的子进程
     * @param bool $blocking 参数可以指定是否阻塞等待，默认为阻塞
     * 操作成功会返回返回一个数组包含子进程的PID、退出状态码、被哪种信号KILL
     * 失败返回false
     * @return array
     */
    public static function wait(bool $blocking = true){}

    /**
     * 使当前进程蜕变为一个守护进程
     * @param bool $nochdir 为true表示不要切换当前目录到根目录
     * @param bool $noclose 为true表示不要关闭标准输入输出文件描述符
     * @return bool
     */
    public static function daemon(bool $nochdir = true, bool $noclose = true){}

    /**
     * 设置异步信号监听
     * 此方法基于signalfd和eventloop是异步IO，不能用于同步程序中
     * 同步阻塞的程序可以使用pcntl扩展提供的pcntl_signal
     * $callback如果为null，表示移除信号监听
     * 如果已设置了此信号的回调函数，重新设置时会覆盖历史设置
     * @param int $signo
     * @param callable $callback
     */
    public static function signal(int $signo, callable $callback){}

    /**
     * 高精度定时器，是操作系统setitimer系统调用的封装，可以设置微秒级别的定时器。定时器会触发信号，需要与swoole_process::signal或pcntl_signal配合使用
     * @param int $interval_usec 定时器间隔时间，单位为微秒。如果为负数表示清除定时器
     * @param int $type 定时器类型，0 表示为真实时间,触发SIGALAM信号，1 表示用户态CPU时间，触发SIGVTALAM信号，2 表示用户态+内核态时间，触发SIGPROF信号
     * @return bool
     */
    public static function alarm(int $interval_usec, int $type = ITIMER_REAL){}

    /**
     * 设置CPU亲和性，可以将进程绑定到特定的CPU核上
     * @param array $cpu_set
     * 接受一个数组参数表示绑定哪些CPU核，如array(0,2,3)表示绑定CPU0/CPU2/CPU3
     * @return bool
     */
    public static function setaffinity(array $cpu_set){}
}