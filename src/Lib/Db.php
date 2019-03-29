<?php
/**
 * This file is part of workerman.
 *
 * @author    libo<libo@usa.com>
 * @copyright libo<libo@usa.com>
 * @link      http://www.niubea.com/
 */

namespace libo\GatewayWorker\Lib;

use Config\Db as DbConfig;
use Exception;

/**
 * 数据库类
 */
class Db
{
    /**
     * 实例数组
     *
     * @var array
     */
    protected static $instance = array();

    /**
     * 获取实例
     *
     * @param string $config_name
     * @return DbConnection
     * @throws Exception
     */
    public static function instance($config_name)
    {
        if (!isset(DbConfig::$$config_name)) {
            echo "\\Config\\Db::$config_name not set\n";
            throw new Exception("\\Config\\Db::$config_name not set\n");
        }

        if (empty(self::$instance[$config_name])) {
            $config                       = DbConfig::$$config_name;
            self::$instance[$config_name] = new DbConnection($config['host'], $config['port'],
                $config['user'], $config['password'], $config['dbname'],$config['charset']);
        }
        return self::$instance[$config_name];
    }

    /**
     * 关闭数据库实例
     *
     * @param string $config_name
     */
    public static function close($config_name)
    {
        if (isset(self::$instance[$config_name])) {
            self::$instance[$config_name]->closeConnection();
            self::$instance[$config_name] = null;
        }
    }

    /**
     * 关闭所有数据库实例
     */
    public static function closeAll()
    {
        foreach (self::$instance as $connection) {
            $connection->closeConnection();
        }
        self::$instance = array();
    }
}
