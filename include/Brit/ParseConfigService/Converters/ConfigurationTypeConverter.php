<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 9:00 AM
 */

namespace Brit\ParseConfigService\Converters;


use Brit\ParseConfigService\Entities\ConfigurationType;
use Brit\ParseConfigService\Repositories\ConfigurationTypeRepository;

/**
 * Class ConfigurationTypeConverter
 * @package Brit\ParseConfigService\Converters
 */
class ConfigurationTypeConverter extends AbstractConverter
{

    /**
     * @var ConfigurationTypeRepository $configurationTypeRepository
     */
    private $configurationTypeRepository;

    /**
     * ConfigurationConverter constructor
     */
    public function __construct() {
        parent::__construct();
        $this->configurationTypeRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationType');
    }

    /**
     * @param $id
     *
     * @return ConfigurationType|null
     * @throws \Exception
     */
    public function convert($id) {
        return $this->configurationTypeRepository->find($id);
    }
}