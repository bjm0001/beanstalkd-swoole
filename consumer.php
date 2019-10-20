<?php

use Pheanstalk\Pheanstalk;

include './vendor/autoload.php';
$workerNum=10;
$pool =new swoole\Process\Pool($workerNum);
$pool->on('WorkerStart',function ($pool,$workerId){
    echo "worker#{$workerId} is started\n";
    try{
        $pda = Pheanstalk::create('127.0.0.1', 11300, 10);
        while (true){
            $job=$pda->watchOnly('consumer-order')->reserve();//没有数据的时候默认是阻塞的
            $data = $job->getData();
            var_dump($data);
            var_dump($pda->delete($job));
            //子进程一点出错，或者抛出异常，就会退出重启
        }
    }catch (Exception $exception){
        echo $exception->getMessage();
    }
});
$pool->on('WorkerStop',function ($pool,$workerId){
    echo "worker#{$workerId} is stop\n";
});
$pool->start();




