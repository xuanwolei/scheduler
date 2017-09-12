# scheduler
php基于yiled实现的并行rpc调度器

### 适用场景 ###

如果你需要同时请求多个api，而且响应时间比较长，并行化调用是个很好的方案，这将为你加快响应速度。

### 版本要求 ###

php >= 5.5
curl扩展

### 使用示例 ###

```php
include "Scheduler/Autoload.php";
use Scheduler\Scheduler;
use Scheduler\Curl;

$time = microtime(true);

$scheduler = new Scheduler;
//第二个参数接收一个回调函数，会把请求的内容返回
$scheduler->newTask(Curl::request("http://demo.xuanwolei.cn/sleep.php"), function($data, Scheduler $scheduler){
	//输入请求返回内容
	var_dump($data);
});
$scheduler->newTask(Curl::request("http://www.ali213.net/"));
$scheduler->newTask(Curl::request("http://www.ali213.net/"));
$scheduler->newTask(Curl::request("http://demo.xuanwolei.cn/sleep.php"));
//运行
$scheduler->run();

//输入运行时间
echo "run time:".(microtime(true) - $time); //3.1秒
```
上面的请求并行化调用耗时在3.1秒左右，下面我们看看串行化调用

```php
include "Scheduler/Autoload.php";
use Scheduler\Scheduler;
use Scheduler\Curl;

$time = microtime(true);

//平常的串行调用
$curl = new Curl();
$result = $curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒
var_dump($result);
$curl->callWebServer("http://www.ali213.net/"); //0.1秒
$curl->callWebServer("http://www.ali213.net/"); //0.1秒
$curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒
$curl->callWebServer("http://demo.xuanwolei.cn/sleep.php"); //3秒

//输入运行时间
echo "run time:".(microtime(true) - $time); //9.3秒
```

一共耗时9.3秒，可见对于响应时间较长的接口并行化调用带来的提升是巨大的
