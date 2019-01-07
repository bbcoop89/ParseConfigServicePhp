<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:53 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PutConfigurationFileAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class PutConfigurationFileAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        /** @var ConfigurationFile $configurationFile */
        $configurationFile = $this->args['configurationFile'];

        $configurationFile->setPath($this->args['path']);

        $this->entityManager->persist($configurationFile);

        $this->entityManager->flush();

        return $this->ok($configurationFile);
    }
}