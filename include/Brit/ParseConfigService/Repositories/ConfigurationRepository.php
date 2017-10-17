<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 7:30 PM
 */

namespace Brit\ParseConfigService\Repositories;


use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Entities\ConfigurationType;
use Doctrine\ORM\EntityRepository;

/**
 * Class ConfigurationRepository
 * @package Brit\ParseConfigService\Repositories
 */
class ConfigurationRepository extends EntityRepository
{
    /**
     * @param array $ids
     * @return Configuration[]
     */
    public function findByIds(array $ids)
    {
        $qb = $this->createQueryBuilder('configuration');

        $qb->select('configurations')
            ->where($qb->expr()->in('configurations.id', ':ids'))
            ->setParameter(':ids', $ids);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param ConfigurationType $type
     * @return Configuration[]
     */
    public function findWithType(ConfigurationType $type)
    {
        $qb = $this->createQueryBuilder('configuration');

        $qb->select('configuration')
            ->where('configuration.type = :type')
            ->setParameter(':type', $type);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param ConfigurationFile $configurationFile
     * @return Configuration[]
     */
    public function findWithFile(ConfigurationFile $configurationFile)
    {
        $qb = $this->createQueryBuilder('configuration');

        $qb->select('configuration')
            ->where(':file MEMBER OF configuration.configurationFiles')
            ->setParameter(':file', $configurationFile);

        return $qb->getQuery()->getResult();
    }
}