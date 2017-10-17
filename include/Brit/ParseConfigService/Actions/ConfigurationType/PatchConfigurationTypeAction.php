<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/20/16
 * Time: 6:42 PM
 */

namespace Brit\ParseConfigService\Actions\ConfigurationType;


use Brit\ParseConfigService\Actions\AbstractAction;
use Brit\ParseConfigService\Entities\Configuration;
use Brit\ParseConfigService\Entities\ConfigurationType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PatchConfigurationTypeAction
 * @package Brit\ParseConfigService\Actions\ConfigurationType
 */
class PatchConfigurationTypeAction extends AbstractAction
{

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        /** @var ConfigurationType $configurationType */
        $configurationType = $this->args['configurationType'];

        $configurationType->setType($this->args['type']);

        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        if(array_key_exists('configurations', $this->args)) {
            if(is_array($this->args['configurations'])) {
                foreach($this->args['configurations'] as $configurationId) {
                    /** @var $configuration Configuration */
                    $configuration = $configurationRepository->find($configurationId);

                    if($configuration === null) {
                        $invalidConfigurations[] = $configurationId;
                    } else {
                        $configuration->setType($configurationType);
                        $configurations[] = $configuration;
                    }
                }
            } else {
                $configuration = $configurationRepository->find($this->args['configurations']);

                if($configuration === null) {
                    $invalidConfigurations[] = $this->args['configurations'];
                } else {
                    $configuration->setType($configurationType);
                    $configurations[] = $configuration;
                }
            }

            if(!empty($invalidConfigurations)) {
                return $this->notFound(
                    'Configurations Not Found: [' . implode(', ', $invalidConfigurations) . '].'
                );
            }
        }

        if(!empty($configurations)) {
            foreach($configurations as $configuration) {
                $configurationType->addConfiguration($configuration);

                $this->entityManager->persist($configuration);
            }

        }

        $this->entityManager->persist($configurationType);

        $this->entityManager->flush();

        return $this->ok($configurationType);
    }
}