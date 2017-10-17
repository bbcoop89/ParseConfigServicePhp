<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:28 PM
 */

namespace Brit\ParseConfigService\Actions\Configuration;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Repositories\ConfigurationFileRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PatchConfigurationAction
 * @package Brit\ParseConfigService\Actions\Configuration
 */
class PatchConfigurationAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        /** @var Configuration $configuration */
        $configuration = $this->args['configuration'];

        if($this->isTypeUpdate()) {
            $configurationTypeRepository =  $this->entityManager
                ->getRepository('Brit\ParseConfigService\Entities\ConfigurationType');

            $configurationType = $configurationTypeRepository->find($this->args['type']);

            if($configurationType === null) {
                return $this->notFound(
                    sprintf('Configuration Type # "%d" Could not Be Found; Configuration not Updated.', $this->args['type'])
                );
            }

            $configuration->setType($configurationType);
        }

        if($this->isKeyUpdate()) {
            $configuration->setKey($this->args['key']);
        }

        if($this->isValueUpdate()) {
            $configuration->setValue($this->args['value']);
        }

        if($this->isFilesUpdate()) {
            /** @var $configurationFileRepository ConfigurationFileRepository */
            $configurationFileRepository =  $this->entityManager
                ->getRepository('Brit\ParseConfigService\Entities\ConfigurationFile');

            if(is_array($this->args['files'])) {
                foreach($this->args['files'] as $fileId) {
                    /** @var $configurationFile ConfigurationFile */
                    $configurationFile = $configurationFileRepository->find($fileId);

                    if($configurationFile === null) {
                        $invalidConfigurationFiles[] = $fileId;
                    } else {
                        $configuration->addConfigurationFile($configurationFile);
                        $configurationFiles[] = $configurationFile;
                    }
                }
            } else {
                /** @var $configurationFile ConfigurationFile */
                $configurationFile = $configurationFileRepository->find($this->args['files']);

                if($configurationFile === null) {
                    $invalidConfigurationFiles[] = $this->args['files'];
                } else {
                    $configuration->addConfigurationFile($configurationFile);
                    $configurationFiles[] = $configurationFile;
                }
            }

            if(!empty($invalidConfigurationFiles)) {
                return $this->notFound(
                    'ConfigurationFiles Not Found: [' . implode(', ', $invalidConfigurationFiles) . '].'
                );
            }
        }

        $this->entityManager->persist($configuration);
        $this->entityManager->flush();

        return $this->ok($configuration);
    }

    /**
     * @return bool
     */
    private function isTypeUpdate()
    {
        return array_key_exists('type', $this->args);
    }

    /**
     * @return bool
     */
    private function isKeyUpdate()
    {
        return array_key_exists('key', $this->args);
    }

    /**
     * @return bool
     */
    private function isValueUpdate()
    {
        return array_key_exists('value', $this->args);
    }

    /**
     * @return bool
     */
    private function isFilesUpdate()
    {
        return array_key_exists('files', $this->args);
    }
}