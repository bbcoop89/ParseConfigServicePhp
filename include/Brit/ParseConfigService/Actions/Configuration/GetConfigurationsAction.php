<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 8:24 PM
 */

namespace Brit\ParseConfigService\Actions\Configuration;


use Brit\ParseConfigService\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetConfigurationsAction
 * @package Brit\ParseConfigService\Actions\Configuration
 */
class GetConfigurationsAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        return $this->ok($configRepository->findAll());
    }
}