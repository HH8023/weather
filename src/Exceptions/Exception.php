<?php

namespace Wandx\Weather\Exceptions;

class Exception extends \Exception
{



}

try {
    $weather->getWeather('深圳');
}catch (\Wandx\Weather\Exceptions\Exception $e) {
    $message = $e->getMessage();

    if($e instanceof \Wandx\Weather\Exceptions\InvalidArgumentException) {
        $message = '参数异常：'.$message;
    } else if ($e instanceof \Wandx\Weather\Exceptions\HttpException) {
        $message = '接口异常：'.$message;
    }

    //其他逻辑...比如发送通知等

    Log::error('调用天气扩展时出现了异常：'.$message);
}