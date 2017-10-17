<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 8:02 AM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationType;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DeleteConfigurationTypeAction
 * @package Brit\ParseConfigService\Actions\ConfigurationType
 */
class DeleteConfigurationTypeAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        /** @var ConfigurationRepository $configurationRepository */
        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        if(!empty($configurationRepository->findWithType($this->args['configurationType']))) {
            return $this->unprocessableEntity("Configuration Type could not be deleted because it has configurations.");
        }

        $this->entityManager->remove($this->args['configurationType']);

        $this->entityManager->flush();

        return $this->noContent();
    }
}