<?php

use Phizzl\phpcrontab\Crontab;
use Phizzl\phpcrontab\SimpleCron;

require_once __DIR__ . '/../vendor/autoload.php';

$crontab = new Crontab();

// Configure the mailer
$crontab->getConfig()->setMailFromAddress('crontab@somedomain.dev');
$crontab->getConfig()->setMailSmtpHost('smtp.somedomain.dev');
$crontab->getConfig()->setMailSmtpUser('awesomeuser');
$crontab->getConfig()->setMailSmtpPass('securePasswrd');
$crontab->getConfig()->setMailSmtpPort(25);
$crontab->getConfig()->setMailSmtpSecure('none');

// Set info which addresses should receive cron infos
$crontab->getConfig()->setGlobalReportRecipients(array(
    'info@somedomain.dev',
    'developer@awesomecode.dev'
));

$cronHelloWorld = new SimpleCron("Say hello world", function(){
    echo "Hello world! It's " . date("H:i:s") . PHP_EOL;
});

// We can add special recipeints per job
$cronHelloWorld->setReportRecipients(array(
    'peter@awesomecode.dev'
));

$cronGoodnightWorld = new SimpleCron("Goodnight!", function(){
    echo "Goodnight. It's " . date("H:i:s") . PHP_EOL;
}, "* 0,1,2,3,4,5,6,22,23 * * *");

// Only send mail on error
$cronGoodnightWorld->setReportStatus(SimpleCron::REPORT_ON_ERROR);

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
