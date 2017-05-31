# php-errors
PHP错误处理组件，可捕获PHP运行时的所有错误，通过设置错误报告级别及日志记录器，将PHP运行时的错误信息记录到日志中。
日志记录接口需遵守 prs-0 规范。支持 dev 模式和 pro 模式，在 pro 模式下错误信息将不会被填充到响应体中。
通过推荐的 php.ini 配置来初始化 PHP 环境，再这之后的所有错误信息交给 php-errors 组件即可。

# 推荐 php.ini 配置（dev & pro）
```bash
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = 错误日志记录位置（绝对路径，也可以设置为 syslog，将日志打印到系统日志中）
error_reporting = E_ALL & E_STRICT
```
#### 上述设置中的 error_log 文件位置仅仅作为备用日志文件地址，当没有设置日志记录器时，日志默认会被填充到该位置（推荐设置日志记录器）。

# 使用
请参考 tests 用例
