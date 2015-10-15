<?php

namespace Footstones\Framework\Service;

use Footstones\Framework\Kernel;
use Footstones\Framework\Exception\ServiceException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseService
{
    const MISSING_PARAMETER = 51002;

    const INVALID_PARAMETER = 51003;

    protected $logger;

    protected function addLog($message, $level, array $context = array())
    {
        if (!in_array($level, array('info', 'notice', 'debug', 'warning', 'error', 'citical', 'alert', 'emrgency'))) {
            throw new ServiceException("Log level is not right,please check!", self::INVALID_PARAMETER);
        }
        $logger = $this->getLogger();
        $func = 'add'.ucfirst($level);
        $logger->$func($message, $context);
    }

    protected function getLogger()
    {
        if (!$this->logger) {
            return $this->createLogger();
        }
        return $this->logger;
    }

    protected function createLogger()
    {
        $logger = new Logger(get_class($this));
        $logName = $this->kernel()->config('log_dir') .'/services.log';
        $logger->pushHandler(new StreamHandler($logName, Logger::WARNING));
        $this->logger = $logger;
        return $logger;
    }

    protected function kernel()
    {
        return Kernel::instance();
    }

    protected function createServiceException($message = 'Service Exception', $code = 0)
    {
        return new ServiceException($message, $code);
    }
}
