<?php

namespace Footstones\Framework;

use Doctrine\DBAL\DriverManager;
use Footstones\Framework\Service\NotFoundService;

class Kernel
{
    private static $_instance = null;

    private $_booted = false;

    protected $_pool = array();

    protected $_config = array();

    public function __construct($config)
    {
        $this->_config = $config;
        self::$_instance = $this;
    }

    public function boot()
    {
        date_default_timezone_set('Asia/Shanghai');
        self::instance();
    }

    public static function instance()
    {
        if (!self::$_instance) {
            throw new \RuntimeException('Kernel is not created.');
        }

        return self::$_instance;
    }

    public function handle()
    {
        try {
            $service = $this->service(empty($_GET['service']) ? 'NotFoundService' : $_GET['service']);
        } catch (\Exception $e) {
            $service = $this->service('NotFoundService');
        }

        $service = new \Yar_Server($service);
        $service->handle();
    }

    public function database()
    {
        $key = '_.database';
        if (!empty($this->_pool[$key])) {
            return $this->_pool[$key];
        }

        $config = $this->_config['database'];

        return $this->_pool[$key] = DriverManager::getConnection(array(
            'dbname' => $config['name'],
            'user' => $config['user'],
            'password' => $config['password'],
            'host' => $config['host'],
            'driver' => $config['driver'],
            'charset' => $config['charset'],
        ));
    }

    public function rpc($entrypoint, $service)
    {
        $key = "_rpc.{$entrypoint}.{$service}";
        if (!empty($this->_pool[$key])) {
            return $this->_pool[$key];
        }

        $config = $this->_config['rpc'];

        if (empty($config['entry_points'][$entrypoint])) {
            throw new RuntimeException("RPC entry point: {$entrypoint} is not found.");
        }

        $url = "{$config['entry_points'][$entrypoint]}?service={$service}";

        $rpc = new \Yar_Client($url);
        $rpc->SetOpt(YAR_OPT_TIMEOUT, $config['timeout']);
        $rpc->SetOpt(YAR_OPT_CONNECT_TIMEOUT, $config['connect_timeout']);

        return $this->_pool[$key] = $rpc;
    }

    public function service($name)
    {
        if ($name == 'NotFoundService') {
            return new NotFoundService();
        }
        
        $class = "{$this->_config['namespace']}\\Service\\{$name}";
        if (!class_exists($class)) {
            throw new \RuntimeException("{$class} is not exist.");
        }

        return new $class();
    }

    public function config($name, $default = null)
    {
        if (!isset($this->_config[$name])) {
            return $default;
        }

        return $this->_config[$name];
    }

    public function dao($name)
    {
        $class = "{$this->_config['namespace']}\\Dao\\{$name}";
        if (!class_exists($class)) {
            throw new \RuntimeException("{$class} is not exist.");
        }

        return new $class();
    }

    protected function pool($name)
    {
        return !empty($this->_pool[$name]) ? $this->_pool[$name] : null;
    }
}
