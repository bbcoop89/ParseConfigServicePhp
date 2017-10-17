<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:52 PM
 */

namespace Brit\ParseConfigService\Actions\Configuration;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PutConfigurationAction
 * @package Brit\ParseConfigService\Actions\Configuration
 */
class PutConfigurationAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configurationFiles = [];
        $invalidFiles = [];

        /** @var Configuration $configuration */
        $configuration = $this->args['configuration'];

        $typeRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationType');
        $fileRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\ConfigurationFile');

        $configurationType = $typeRepository->find($this->args['type']);

        if($configurationType === null) {
            return $this->notFound(
                sprintf('Configuration Type # "%d" was not Found', $this->args['type'])
            );
        }

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
                return $this->notFound(
                    sprintf('Configuration File Not Found: "%d"', $this->args['files'])
                );
            } else {
                $configurationFiles[] = $configurationFile;
            }
        }


        if(!empty($invalidFiles)) {
            return $this->notFound(
                sprintf('Configuration Files Not Found: [', implode(', ', $invalidFiles) . '].')
            );
        }

        $configuration->setKey($this->args['key']);
        $configuration->setValue($this->args['value']);
        $configuration->setType($configurationType);
        $configuration->setConfigurationFiles($configurationFiles);

        $this->entityManager->persist($configuration);

        $this->entityManager->flush();

        return $this->ok($configuration);
    }
}