<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:53 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationType;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationType;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PutConfigurationTypeAction
 * @package Brit\ParseConfigService\Actions\ConfigurationType
 */
class PutConfigurationTypeAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {

        /** @var ConfigurationType $configurationType */
        $configurationType = $this->args['configurationType'];

        $configurationType->setType($this->args['type']);

        $this->entityManager->persist($configurationType);

        $this->entityManager->flush();

        return $this->ok($configurationType);
    }
}