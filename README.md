PHP Crontab
===========

This is a simple PHP implementation for scheduled jobs.

You may us it like 

```php
<?php
 
 use Phizzl\phpcrontab\Crontab;
 use Phizzl\phpcrontab\SimpleCron;
 
 require_once __DIR__ . '/../vendor/autoload.php';
 
 $crontab = new Crontab();
 
 $cronHelloWorld = new SimpleCron("Say hello world", function(){
     echo "Hello world! It's " . date("H:i:s") . PHP_EOL;
 });
 
 $cronGoodnightWorld = new SimpleCron("Goodnight!", function(){
     echo "Goodnight. It's " . date("H:i:s") . PHP_EOL;
 }, "* 0,1,2,3,4,5,6,22,23 * * *");
 
 $cronGoodMorningWorld = new SimpleCron("Goodmorning!", function(){
     echo "Goodmorning. It's " . date("H:i:s") . PHP_EOL;
 }, "* 7-11 * * *");
 
 $cronGoodDayWorld = new SimpleCron("Goodday!", function(){
     echo "Wish you a good Day. It's " . date("H:i:s") . PHP_EOL;
 }, "* 11-21 * * *");
 
 $cronWeekend = new SimpleCron("Yay it's weekend", function(){
     echo "Yay! It's weekend!. It's " . date("H:i:s") . PHP_EOL;
 }, "* * * * 6,7");
 
 $crontab->addCron($cronHelloWorld);
 $crontab->addCron($cronGoodnightWorld);
 $crontab->addCron($cronGoodMorningWorld);
 $crontab->addCron($cronGoodDayWorld);
 $crontab->addCron($cronWeekend);
 
 $crontab->run();
```

To run the queue You may add a script to your Linux crontab or you Windows scheduled tasks to run every minute.