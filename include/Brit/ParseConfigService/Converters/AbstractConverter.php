<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 8:55 AM
 */

namespace Brit\ParseConfigService\Converters;


use Brit\Library\ApplicationSettings;
use Doctrine\ORM\EntityManager;

/**
 * Class AbstractConverter
 * @package Brit\ParseConfigService\Converters
 */
abstract class AbstractConverter
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * AbstractConverter constructor.
     */
    public function __construct() {
        $this->entityManager = ApplicationSettings::getSetting('orm.entity.manager');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    abstract public function convert($id);
}