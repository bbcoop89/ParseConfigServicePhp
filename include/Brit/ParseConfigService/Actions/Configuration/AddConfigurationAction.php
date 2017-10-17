<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 12:12 PM
 */

namespace Brit\ParseConfigService\Actions\Configuration;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class AddConfigurationAction
 * @package Brit\ParseConfigService\Actions\Configuration
 */
class AddConfigurationAction extends AbstractAction
{
    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $invalidFiles = [];

        /** @var ConfigurationFile[] $configurationFiles */
        $configurationFiles = [];

        $fileRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationFile');
        $typeRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationType');

        $configurationType = $typeRepository->find($this->args['type']);

        if($configurationType === null) {
            return $this->notFound(
                sprintf('Configuration Type # "%d" was not Found', $this->args['type'])
            );
        }

        if(array_key_exists('files', $this->args)) {
            if(is_array($this->args['files'])) {
                foreach($this->args['files'] as $fileId) {
                    $configurationFile = $fileRepository->find($fileId);

                    if($configurationFile === null) {
                        $invalidFiles[] = $fileId;
                    } else {
                        $configurationFiles[] = $configurationFile;
                    }
                }
            } else {
                $configurationFile = $fileRepository->find($this->args['files']);

                if($configurationFile === null) {
                    $invalidFiles[] = $this->args['files'];
                } else {
                    $configurationFiles[] = $configurationFile;
                }
            }
        }

        if(!empty($invalidFiles)) {
            return $this->notFound(
                'Configuration Files Not Found: [' . implode(', ', $invalidFiles) . '].'
            );
        }

        $configuration = new Configuration();

        $configuration->setKey($this->args['key']);
        $configuration->setValue($this->args['value']);
        $configuration->setType($configurationType);

        foreach($configurationFiles as $configurationFile) {
            $configuration->addConfigurationFile($configurationFile);
        }

        $this->entityManager->persist($configuration);

        $this->entityManager->flush();

        $configurationId = $configuration->getId();

        $configurationLocation = 'configurations/' . $configurationId;

        $subRequest = Request::create(
            '/configurations/' . $configurationId,
            'GET'
        );

        /** @var Response $createdResource */
        $createdResource = $this->app->handle(
            $subRequest,
            HttpKernelInterface::SUB_REQUEST,
            false
        );

        return $this->created(
            $configurationLocation,
            json_decode($createdResource->getContent())
        );
    }
}