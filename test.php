<?php
include "Scheduler/Autoload.php";
use Scheduler\Scheduler;
use Scheduler\Curl;

$time = microtime(true);

//并行化调用
$scheduler = new Scheduler;
/**
 *	第一个参数接受一个迭代生成器
 *  第二个参数接收一个回调函数，会把请求的内容返回
 */
$scheduler->newTask(Curl::request("http://demo.xuanwolei.cn/sleep.php"), function($data, Scheduler $scheduler){
	var_dump($data);
});
$scheduler->newTask(Curl::request("http://www.ali213.net/"));
$scheduler->newTask(Curl::request("http://www.ali213.net/"));
$scheduler->newTask(Curl::request("http://demo.xuanwolei.cn/sleep.php"));
$scheduler->newTask(Curl::request("http://demo.xuanwolei.cn/sleep.php"));
//加入2个生成器
$scheduler->newTask(generator());
$scheduler->newTask(generator());
//运行
$scheduler->run();
//共耗时3.1秒

//平常的串行调用
// $curl = new Curl();
// $result = $curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒
// var_dump($result);
// $curl->callWebServer("http://www.ali213.net/"); //0.1秒
// $curl->callWebServer("http://www.ali213.net/"); //0.1秒
// $curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒
// $curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒
//共耗时9.3秒

//输出运行时间
echo "run time:".bcsub(microtime(true),$time,2);

/**
 *	生成器
 */
function generator(){
	for ($i=0; $i < 10; $i++) {
		//这里可以是业务逻辑，假设每次需要0.1秒
		usleep(100000);
		yield;
	}
}
