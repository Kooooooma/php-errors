# php-errors
PHP错误处理组件，可捕获PHP运行时的所有错误，通过设置错误报告级别及日志记录器，将PHP运行时的错误信息记录到日志中。
日志记录接口需遵守 prs-0 规范。支持通过设置 displayErrors 参数为 true 将错误信息打印到页面上。  
通过推荐的 php.ini 配置来初始化 PHP 环境，再这之后的所有错误信息交给 php-errors 组件即可。  

# 推荐 php.ini 配置（dev & pro）
```bash
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = 错误日志记录位置（绝对路径，也可以设置为 syslog，将日志打印到系统日志中）
error_reporting = E_ALL & E_STRICT

//php-fpm 环境下还应该设置 php-fpm 的配置，如下：通常位于 /path/to/fpm/pool.d/www.conf
catch_workers_output = yes
```
#### 上述设置中的 error_log 文件位置仅仅作为备用日志文件地址，当没有设置日志记录器时，日志默认会被填充到该位置（推荐设置日志记录器）。

# install
```bash
{
    "require": {
        "php-errors/php-errors": "~1.0"
    }
}
```

# 使用
```php
$baseDir = dirname(__DIR__);
require $baseDir.'/vendor/autoload.php';

$errorHandler = \PHPErrors\PHPErrors::enable(E_ALL, true);

//以下代码可选，用来设置日志记录器（推荐这么做）
$logger = new \Monolog\Logger("test");
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__."/test.log"));
$errorHandler->setLogger($logger);
```

# 要求    
应用代码中对于应用异常处理需要通过 try/catch 代码块来捕获，否则异常会被 php-errors 捕获并记录到日志中。
