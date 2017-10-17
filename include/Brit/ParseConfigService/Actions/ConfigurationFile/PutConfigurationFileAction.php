<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:53 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Brit\ParseConfigService\Repositories\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PutConfigurationFileAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class PutConfigurationFileAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $configurations = [];
        $invalidConfigurations = [];

        /** @var ConfigurationFile $configurationFile */
        $configurationFile = $this->args['configurationFile'];

        /** @var ConfigurationRepository $configurationRepository */
        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        if(array_key_exists('configurations', $this->args)) {
            $existingConfigurations = $configurationFile->getConfigurations();

            foreach($existingConfigurations as $configuration) {
                $configuration->removeConfigurationFile($configurationFile);
            }
            if(is_array($this->args['configurations'])) {
                foreach($this->args['configurations'] as $configurationId) {
                    /** @var Configuration $configuration */
                    $configuration = $configurationRepository->find($configurationId);

                    if($configuration === null) {
                        $invalidConfigurations[] = $configurationId;
                    } else {
                        $configuration->addConfigurationFile($configurationFile);
                        $configurations[] = $configuration;
                    }
                }
            } else {
                $configuration = $configurationRepository->find($this->args['configurations']);

                if($configuration === null) {
                    $invalidConfigurations[] = $this->args['configurations'];
                } else {
                    $configuration->addConfigurationFile($configurationFile);
                    $configurations[] = $configuration;
                }
            }

            if(!empty($invalidConfigurations)) {
                return $this->notFound(
                    'Configurations Not Found: [' . implode(', ', $invalidConfigurations) . '].'
                );
            }
        }

        $configurationFile->setPath($this->args['path']);

        foreach($configurations as $configuration) {
            $this->entityManager->persist($configuration);
        }

        $this->entityManager->persist($configurationFile);

        $this->entityManager->flush();

        return $this->ok($configurationFile);
    }
}