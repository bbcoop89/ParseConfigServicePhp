<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 8:01 AM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DeleteConfigurationFileAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class DeleteConfigurationFileAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        /** @var ConfigurationRepository $configurationRepository */
        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        if(!empty($configurationRepository->findWithFile($this->args['configurationFile']))) {
            return $this->unprocessableEntity("Configuration File could not be deleted because it has configurations.");
        }

        $this->entityManager->remove($this->args['configurationFile']);

        $this->entityManager->flush();

        return $this->noContent();
    }
}