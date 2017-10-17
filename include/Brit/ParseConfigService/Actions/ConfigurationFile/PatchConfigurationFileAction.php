<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:41 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationFile;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationFile;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PatchConfigurationFileAction
 * @package Brit\ParseConfigService\Actions\ConfigurationFile
 */
class PatchConfigurationFileAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $invalidConfigurations = [];
        $configurations = [];

        /** @var ConfigurationFile $configurationFile */
        $configurationFile = $this->args['configurationFile'];

        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        if(array_key_exists('configurations', $this->args)) {
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

            if(!empty($invalidConfigurations)) {
                return $this->notFound(
                    'Configurations Not Found: [' . implode(', ', $invalidConfigurations) . '].'
                );
            }
        }


        $configurationFile->setPath($this->args['path']);

        /** @var $configurations Configuration[] */
        if(!empty($configurations)) {
            foreach($configurations as $configuration) {
                $configuration->addConfigurationFile($configurationFile);

                $this->entityManager->persist($configuration);
            }
        }

        $this->entityManager->persist($configurationFile);

        $this->entityManager->flush();

        return $this->ok($configurationFile);
    }
}