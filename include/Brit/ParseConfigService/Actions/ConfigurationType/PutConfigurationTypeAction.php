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
//        $configurations = [];

        /** @var ConfigurationType $configurationType */
        $configurationType = $this->args['configurationType'];

        /** @var ConfigurationRepository $configurationRepository */
//        $configurationRepository = $this->entityManager->getRepository('Brit\ParseConfigService\Entities\Configuration');

        $configurationType->setType($this->args['type']);

//        if(array_key_exists('configurations', $this->args)) {
//            $existingConfigurations = $configurationType->getConfigurations();
//
//            foreach($existingConfigurations as $configuration) {
//                $configurationType->removeConfiguration($configuration);
//            }
//
//            if(is_array($this->args['configurations'])) {
//                foreach($this->args['configurations'] as $configurationId) {
//                    /** @var Configuration $configuration */
//                    $configuration = $configurationRepository->find($configurationId);
//
//                    if($configuration === null) {
//                        $invalidConfigurations[] = $configurationId;
//                    } else {
//                        $configuration->setType($configurationType);
//                        $configurationType->addConfiguration($configuration);
//                        $configurations[] = $configuration;
//                    }
//                }
//            } else {
//                /** @var Configuration $configuration */
//                $configuration = $configurationRepository->find($this->args['configurations']);
//
//                if($configuration === null) {
//                    $invalidConfigurations[] = $this->args['configurations'];
//                } else {
//                    $configuration->setType($configurationType);
//                    $configurationType->addConfiguration($configuration);
//                    $configurations[] = $configuration;
//                }
//            }
//
//            if(!empty($invalidConfigurations)) {
//                return $this->notFound(
//                    'Configurations Not Found: [' . implode(', ', $invalidConfigurations) . '].'
//                );
//            }
//        }



//        foreach($configurations as $configuration) {
//            $this->entityManager->persist($configuration);
//        }

        $this->entityManager->persist($configurationType);

        $this->entityManager->flush();

        return $this->ok($configurationType);
    }
}