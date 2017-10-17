<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 8:55 AM
 */

namespace Brit\ParseConfigService\Converters;


use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;

/**
 * Class ConfigurationConverter
 * @package Brit\ParseConfigService\Converters
 */
class ConfigurationConverter extends AbstractConverter
{
    /**
     * @var ConfigurationRepository $configurationRepository
     */
    private $configurationRepository;

    /**
     * ConfigurationConverter constructor
     */
    public function __construct() {
        parent::__construct();
        $this->configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');
    }

    /**
     * @param $id
     *
     * @return Configuration|null
     * @throws \Exception
     */
    public function convert($id) {
        return $this->configurationRepository->find($id);
    }
}