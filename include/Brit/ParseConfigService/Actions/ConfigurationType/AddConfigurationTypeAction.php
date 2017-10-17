<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 12:41 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationType;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\ConfigurationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class AddConfigurationTypeAction
 * @package Brit\ParseConfigService\Actions\ConfigurationType
 */
class AddConfigurationTypeAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configurationType = new ConfigurationType();

        $configurationType->setType($this->args['type']);

        $this->entityManager->persist($configurationType);

        $this->entityManager->flush($configurationType);

        $configurationTypeId = $configurationType->getId();

        $configurationTypeLocation = 'configurationTypes/' . $configurationTypeId;

        $subRequest = Request::create(
            '/configurationTypes/' . $configurationTypeId,
            'GET'
        );

        /** @var Response $createdResource */
        $createdResource = $this->app->handle(
            $subRequest,
            HttpKernelInterface::SUB_REQUEST,
            false
        );

        return $this->created(
            $configurationTypeLocation,
            json_decode($createdResource->getContent())
        );
    }
}