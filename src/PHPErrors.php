<?php

/**
 * php-error for catch all php running time errors.
 *
 * @author Koma <komazhang@foxmail.com>
 * @date 2017-05-31
 *
 * @copyright Koma
 *
 */
namespace PHPErrors;

use Psr\Log\LogLevel;

class PHPErrors
{
    public $logger;
    public $displayErrors = false;
    public static $errorReportLevel = E_ALL;

    public function __construct($displayErrors = false)
    {
        $this->displayErrors = $displayErrors;
    }

    public static function enable($errorReportLevel = E_ALL, $displayErrors = false)
    {
        if ($errorReportLevel != null) {
            error_reporting($errorReportLevel);
            self::$errorReportLevel = $errorReportLevel;
        } else {
            error_reporting(E_ALL);
        }

        ini_set('log_errors', 1);

        $handler = new static($displayErrors);
        return $handler->register();
    }

    public function register()
    {
        register_shutdown_function(array($this, 'handleFatalError'));
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));

        return $this;
    }

    public function log($type, $message)
    {
        $level = $this->getErrorLevel($type);

        if ( $this->logger != null ) {
            $this->logger->log($level, $message);
        } else {
            error_log("{$level}: {$message}");
        }

        if ( $this->displayErrors ) {
            print "{$level}: {$message}";
        }
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function handleFatalError()
    {
        $error = error_get_last();

        if (!empty($error)) {
            $type = $error['type'];

            if ($type & self::$errorReportLevel) {
                $message = $this->formatMessage($error['message'], $error['file'], $error['line']);

                $this->log($type, $message);
            }
        }
    }

    public function handleError($type, $message, $file, $line)
    {
        if ($type & self::$errorReportLevel) {
            $message = $this->formatMessage($message, $file, $line);

            $this->log($type, $message);
        }
    }

    public function handleException(\Throwable $exception)
    {
        //把所有Error都当做成是致命的错误处理
        if ($exception instanceof \Error) {
            $exception = new \ErrorException(
                $exception->getMessage(),
                $exception->getCode(),
                E_ERROR,
                $exception->getFile(),
                $exception->getLine()
            );
        }

        if ($exception instanceof \Exception) {
            $type = $exception instanceof \ErrorException ? $exception->getSeverity() : E_ERROR;

            if ($type & self::$errorReportLevel) {
                $message = $this->formatMessage(
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getTraceAsString()
                );

                $this->log($type, $message);
            }
        }
    }

    public function formatMessage($message, $file, $line, $trace = '')
    {
        $message = "{$file}#{$line}: {$message}";
        return <<<MSG
$message
$trace
MSG;
    }

    public function getErrorLevel($type)
    {
        $level = LogLevel::EMERGENCY;

        switch ($type) {
            case E_ERROR:
            case E_CORE_ERROR:
                $level = LogLevel::CRITICAL;
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_CORE_WARNING:
            case E_USER_WARNING:
                $level = LogLevel::WARNING;
                break;
            case E_PARSE:
            case E_COMPILE_ERROR:
                $level = LogLevel::ALERT;
                break;
            case E_NOTICE:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_NOTICE:
            case E_USER_DEPRECATED:
                $level = LogLevel::NOTICE;
                break;
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $level = LogLevel::ERROR;
                break;
        }

        return $level;
    }
}
