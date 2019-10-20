<?php

use Pheanstalk\Pheanstalk;

include './vendor/autoload.php';
$tubeName = 'consumer-order';
$pda = Pheanstalk::create('127.0.0.1', 11300, 10);
$pda = $pda->useTube($tubeName);
swoole_timer_tick(2000, function ($timer_id) use ($pda) {
    $orderNo = uniqid();
    if($pda->put($orderNo, 1024, 0, 60)){
        echo "订单编号:{$orderNo}\r\n";
    }
});
