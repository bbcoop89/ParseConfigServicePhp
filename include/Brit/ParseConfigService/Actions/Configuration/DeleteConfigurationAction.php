<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 7:29 AM
 */

namespace Brit\ParseConfigService\Actions\Configuration;


use Brit\ParseConfigService\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DeleteConfigurationAction
 * @package Brit\ParseConfigService\Actions\Configuration
 */
class DeleteConfigurationAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $this->entityManager->remove($this->args['configuration']);

        $this->entityManager->flush();

        return $this->noContent();
    }
}