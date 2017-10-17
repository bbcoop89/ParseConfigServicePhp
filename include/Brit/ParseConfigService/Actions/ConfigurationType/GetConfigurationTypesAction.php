<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/19/16
 * Time: 9:53 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationType;


use Brit\ParseConfigService\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetConfigurationTypesAction
 * @package Brit\ParseConfigService\Actions\ConfigurationType
 */
class GetConfigurationTypesAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationType');

        return $this->ok($configRepository->findAll());
    }
}