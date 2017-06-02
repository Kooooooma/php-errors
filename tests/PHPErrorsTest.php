<?php
$baseDir = dirname(__DIR__);

require $baseDir.'/vendor/autoload.php';

$errorHandler = \PHPErrors\PHPErrors::enable(E_ALL & ~E_WARNING, true);

//$logger = new \Monolog\Logger("test");
//$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__."/test.log"));
//
//$errorHandler->setLogger($logger);


//test error
$a = 1/0;
echo @$x; //E_NOTICE
@$fatal->error(); //E_ERROR
//echo $y;

//throw new \Exception("dfdsd");

try {
    throw new \Exception("dfdsd");
} catch (\Exception $e) {

}