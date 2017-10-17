<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/19/16
 * Time: 9:56 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetConfigurationFilesAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class GetConfigurationFilesAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationFile');

        return $this->ok($configRepository->findAll());
    }
}