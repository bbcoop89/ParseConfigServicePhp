<?php

namespace Brit\Library;

use Brit\Library\Exceptions\ApplicationSettingNotFoundException;
use Doctrine\ORM\EntityManager;

/**
 * Class ApplicationSettings
 * @package Brit\Library
 */
class ApplicationSettings
{
    /**
     * @var array $applicationSettings
     */
    private static $applicationSettings;

    /**
     * @var string $path
     */
    private static $path;

    /**
     * @param array $configurations
     */
    public static function init(array $configurations)
    {
        foreach($configurations as $key => $value) {
            self::$applicationSettings[$key] = $value;
        }
    }

    /**
     * @param string $path
     */
    public static function setPath($path) {
        self::$path = $path;
    }

    public static function load()
    {
        self::$path = __DIR__ . '/../../../../config';
        self::$applicationSettings = require(self::$path . '/config.php');

        self::init(self::$applicationSettings);
    }

    /**
     * @param EntityManager $entityManager
     */
    public static function setEntityManager(EntityManager $entityManager)
    {
        self::$applicationSettings['orm.entity.manager'] = $entityManager;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ApplicationSettingNotFoundException
     */
    public static function getSetting($key)
    {
        if(!array_key_exists($key, self::$applicationSettings)) {
            throw new ApplicationSettingNotFoundException(
                sprintf("Application setting ['%s'] not found.", $key)
            );
        }

        return self::$applicationSettings[$key];
    }

    /**
     * @return array
     */
    public static function getApplicationSettings()
    {
        return self::$applicationSettings;
    }

    /**
     * @param array $applicationSettings
     */
    public static function setApplicationSettings(array $applicationSettings)
    {
        self::$applicationSettings = $applicationSettings;
    }
}