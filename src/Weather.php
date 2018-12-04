<?php

namespace Wandx\Weather;

use GuzzleHttp\Client;//获取天气要用http请求，所以先创建一个方法用于返回guzzle实例
use Wandx\Weather\Exceptions\HttpException;
use Wandx\Weather\Exceptions\InvalidArgumentException;


class Weather
{
    protected $key;
    protected $guzzleOptions = [];

    /*
     * 调用天气API需要用到API key ，所以设计到构造函数中
     * */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /*
     * HTTP 客户端方法
     * */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /*
     *
     * */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    //
    public function getWeather($city, $type = 'base', $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        // 1. 对 `$format` 与 `$extensions` 参数进行检查，不在范围内的抛出异常.
        if(!\in_array($format,['xml','json'])) {
            throw new InvalidArgumentException('Invalid response format: '.$format);
        }

        if(!\in_array(\strtolower($type),['base','all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): '.$type);
        }

        //2、封装query参数，并对空值进行过滤。
        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => $format,
            'extensions' => $type,
        ]);
        try {
            //3、调用getHttpClient获取实例，并调用该实例的‘get’方法，
            //传递参数为两个：$url、['query' => $query],
            $response = $this->getHttpClient()->get($url,[
                'query' => $query,
            ])->getBody()->getContents();

            //4、返回值根据$format返回不同的格式，
            //当$format为json时，返回数组格式，否则为xml。
            return $format === 'json' ? \json_decode($response,true) : $response;
        } catch (\Exception $e) {
            //5、当调用出现异常时捕获并抛出，消息为捕获到的异常消息，
            //并将调用异常作为 $previousException 传入。
            throw new HttpException($e->getMessage(),$e->getCode(),$e);
        }
    }






}