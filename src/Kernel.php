<?php

namespace Footstones\Framework;

use Doctrine\DBAL\DriverManager;
use Footstones\Framework\Service\NotFoundService;
use Pimple\Container;

abstract class Kernel
{
    private static $_instance = null;

    protected $_config = array();

    protected $container = null;

    abstract function getNamespace();

    abstract function boot();

    public function __construct($config)
    {
        $this->_config = $config;
        $this->container = new Container();
        self::$_instance = $this;
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

        $server = new \Yar_Server($service);
        return $server->handle();
    }

    public function service($name)
    {
        if (!isset($this->container[$name])) {
            if ($name == 'NotFoundService') {
                return new NotFoundService();
            }
            $class = "{$this->getNamespace()}\\Service\\{$name}";
            $this->container[$name] =  new $class();
        }
        return $this->container[$name];
    }

    public function dao($name)
    {
        if (!isset($this->container[$name])) {
            $class = "{$this->getNamespace()}\\Dao\\{$name}";
            $this->container[$name] =  new $class();
        }
        return $this->container[$name];
    }

    public function rpc($entrypoint, $service)
    {
        $id = "_rpc.{$entrypoint}.{$service}";

        if (!isset($this->container[$id])) {
            $config = $this->_config['rpc'];

            if (empty($config['entry_points'][$entrypoint])) {
                throw new \RuntimeException("RPC entry point: {$entrypoint} is not found.");
            }

            $url = "{$config['entry_points'][$entrypoint]}?service={$service}";

            $rpc = new \Yar_Client($url);
            $rpc->SetOpt(YAR_OPT_TIMEOUT, empty($config['timeout']) ? '5000' : $config['timeout']);
            $rpc->SetOpt(YAR_OPT_PACKAGER, empty($config['packager']) ? 'php' : $config['packager']);
            $rpc->SetOpt(YAR_OPT_CONNECT_TIMEOUT, empty($config['connect_timeout']) ? '2000' : $config['connect_timeout']);

            $this->container[$id] = $rpc;
        }

        return $this->container[$id];
    }

    public function config($name, $default = null)
    {
        if (!isset($this->_config[$name])) {
            return $default;
        }

        return $this->_config[$name];
    }

    public function database()
    {
        $id = '_.database';
        if (!isset($this->container[$id])) {
            $config = $this->_config['database'];

            $this->container[$id] = DriverManager::getConnection(array(
                'dbname' => $config['name'],
                'user' => $config['user'],
                'password' => $config['password'],
                'host' => $config['host'],
                'driver' => $config['driver'],
                'charset' => $config['charset'],
            ));
        }

        return $this->container[$id];
    }

}
