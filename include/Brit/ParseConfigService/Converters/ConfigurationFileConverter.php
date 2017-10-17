<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 8:59 AM
 */

namespace Brit\ParseConfigService\Converters;


use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Repositories\ConfigurationFileRepository;

/**
 * Class ConfigurationFileConverter
 * @package Brit\ParseConfigService\Converters
 */
class ConfigurationFileConverter extends AbstractConverter
{
    /**
     * @var ConfigurationFileRepository $configurationFileRepository
     */
    private $configurationFileRepository;

    /**
     * ConfigurationConverter constructor
     */
    public function __construct() {
        parent::__construct();
        $this->configurationFileRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationFile');
    }

    /**
     * @param $id
     *
     * @return ConfigurationFile|null
     * @throws \Exception
     */
    public function convert($id) {
        return $this->configurationFileRepository->find($id);
    }
}