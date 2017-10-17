<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 6:45 PM
 */

namespace Brit\Library;

use Brit\Library\Exceptions\ApplicationSettingNotFoundException;


/**
 * Class DatabaseSettings
 * @package Brit\Library
 */
class DatabaseSettings
{
    /**
     * @var \PDO $mysqlPdo
     */
    private static $mysqlPdo;

    /**
     * @return \PDO
     * @throws ApplicationSettingNotFoundException
     */
    public static function getMySqlPdo()
    {
        if(self::$mysqlPdo instanceof \PDO) {
            return self::$mysqlPdo;
        } else {
            self::$mysqlPdo = new \PDO(
                'mysql:host=' . Config::getSetting('mysql.host') . ';dbname=' . Config::getSetting('mysql.dbname'),
                Config::getSetting('mysql.username'),
                Config::getSetting('mysql.password')
            );

            return self::$mysqlPdo;
        }
    }
}