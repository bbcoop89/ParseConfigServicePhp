<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 12:41 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class AddConfigurationFileAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class AddConfigurationFileAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configurations = [];
        $invalidConfigurations = [];


        if(array_key_exists('configurations', $this->args)) {
            /** @var ConfigurationRepository $configurationRepository */
            $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

            if(is_array($this->args['configurations'])) {
                foreach($this->args['configurations'] as $configurationId) {
                    $configuration = $configurationRepository->find($configurationId);

                    if($configuration === null) {
                        $invalidConfigurations[] = $configurationId;
                    } else {
                        $configurations[] = $configuration;
                    }
                }
            } else {
                $configuration = $configurationRepository->find($this->args['configurations']);

                if($configuration === null) {
                    $invalidConfigurations[] = $this->args['configurations'];
                } else {
                    $configurations[] = $configuration;
                }
            }
        }

        if(!empty($invalidConfigurations)) {
            return $this->notFound('Configurations not Found: [' . implode(', ', $invalidConfigurations) . ']');
        }

        $configurationFile = new ConfigurationFile();
        $configurationFile->setPath($this->args['path']);
        $configurationFile->setConfigurations($configurations);

        $this->entityManager->persist($configurationFile);

        $this->entityManager->flush();

        $configurationFileId = $configurationFile->getId();

        $configurationFileLocation = 'configurationFiles/' . $configurationFileId;

        $subRequest = Request::create(
            '/configurationFiles/' . $configurationFileId,
            'GET'
        );

        /** @var Response $createdResource */
        $createdResource = $this->app->handle(
            $subRequest,
            HttpKernelInterface::SUB_REQUEST,
            false
        );

        return $this->created(
            $configurationFileLocation,
            json_decode($createdResource->getContent())
        );
    }
}