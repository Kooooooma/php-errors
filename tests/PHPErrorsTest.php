<?php
$baseDir = dirname(__DIR__);

require $baseDir.'/vendor/autoload.php';

$errorHandler = \PHPErrors\PHPErrors::enable(E_ALL & E_STRICT, false);

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__."/test.log"));

$errorHandler->setLogger($logger);


//test error
echo $x;
$fatal->error();
