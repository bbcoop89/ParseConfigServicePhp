<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 6:57 PM
 */

namespace Brit\ParseConfigService\Controllers;


use Brit\ParseConfigService\Actions\Configuration\AddConfigurationAction;
use Brit\ParseConfigService\Actions\Configuration\DeleteConfigurationAction;
use Brit\ParseConfigService\Actions\Configuration\GetConfigurationsAction;
use Brit\ParseConfigService\Actions\Configuration\PatchConfigurationAction;
use Brit\ParseConfigService\Actions\Configuration\PutConfigurationAction;
use Brit\ParseConfigService\Actions\ConfigurationFile\AddConfigurationFileAction;
use Brit\ParseConfigService\Actions\ConfigurationFile\DeleteConfigurationFileAction;
use Brit\ParseConfigService\Actions\ConfigurationFile\GetConfigurationFilesAction;
use Brit\ParseConfigService\Actions\ConfigurationFile\PatchConfigurationFileAction;
use Brit\ParseConfigService\Actions\ConfigurationFile\PutConfigurationFileAction;
use Brit\ParseConfigService\Actions\ConfigurationType\AddConfigurationTypeAction;
use Brit\ParseConfigService\Actions\ConfigurationType\DeleteConfigurationTypeAction;
use Brit\ParseConfigService\Actions\ConfigurationType\GetConfigurationTypesAction;
use Brit\ParseConfigService\Actions\ConfigurationType\PatchConfigurationTypeAction;
use Brit\ParseConfigService\Actions\ConfigurationType\PutConfigurationTypeAction;
use Brit\ParseConfigService\Converters\ConfigurationConverter;
use Brit\ParseConfigService\Converters\ConfigurationFileConverter;
use Brit\ParseConfigService\Converters\ConfigurationTypeConverter;
use Brit\ParseConfigService\ErrorHandlers\ParseConfigErrorHandler;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParseConfigServiceController
 * @package Brit\ParseConfigService\Controllers
 */
class ParseConfigServiceController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request)
    {
        $app = new Application();

        $errorHandler = new ParseConfigErrorHandler();
        $errorHandler->handle($request, $app);

        $app['debug'] = true;

        $this->initConverters($app);

        /** GETS */
        $app->get(
            '/configurations',
            function () use ($app, $request) {
                $action = new GetConfigurationsAction($app, $request);
                return $action->execute();

            }
        );

        $app->get(
            '/configurationTypes',
            function () use($app, $request) {
                $action = new GetConfigurationTypesAction($app, $request);
                return $action->execute();
            }
        );

        $app->get(
            '/configurationFiles',
            function () use($app, $request) {
                $action = new GetConfigurationFilesAction($app, $request);
                return $action->execute();
            }
        );

        /** Entity */
        $app->get(
            '/configurations/{configuration}',
            function ($configuration) use ($app, $request) {
                if($configuration !== null) {
                    return new JsonResponse($configuration);
                } else {
                    return new JsonResponse('Configuration was not Found', Response::HTTP_NOT_FOUND);
                }

            }
        )->convert('configuration', 'converter.configuration:convert');

        $app->get(
            '/configurationTypes/{configurationType}',
            function ($configurationType) use($app, $request) {
                if($configurationType !== null) {
                    return new JsonResponse($configurationType);
                } else {
                    return new JsonResponse('Configuration Type was not Found', Response::HTTP_NOT_FOUND);
                }
            }
        )->convert('configurationType', 'converter.configuration.type:convert');

        $app->get(
            '/configurationFiles/{configurationFile}',
            function ($configurationFile) use($app, $request) {
                if($configurationFile !== null) {
                    return new JsonResponse($configurationFile);
                } else {
                    return new JsonResponse('Configuration File was not Found', Response::HTTP_NOT_FOUND);
                }
            }
        )->convert('configurationFile', 'converter.configuration.file:convert');

        /** POSTS */
        $app->post(
            '/configurations',
            function() use ($app, $request) {
                $data = json_decode($request->getContent());

                $args = [
                    'type' => $data->type,
                    'key' => $data->key,
                    'value' => $data->value
                ];

                if(property_exists($data, 'files')) {
                    $args['files'] = $data->files;
                }

                $action = new AddConfigurationAction($app, $request, $args);
                return $action->execute();
            }
        );

        $app->post(
            '/configurationTypes',
            function() use($app, $request) {
                $data = json_decode($request->getContent());

                $args = [
                    'type' => $data->type
                ];

                $action = new AddConfigurationTypeAction($app, $request, $args);
                return $action->execute();
            }
        );

        $app->post(
            '/configurationFiles',
            function() use ($app, $request) {
                $data = json_decode($request->getContent());

                $args = [
                    'path' => $data->path
                ];

                if(property_exists($data, 'configurations')) {
                    $args['configurations'] = $data->configurations;
                }

                $action = new AddConfigurationFileAction($app, $request, $args);
                return $action->execute();
            }
        );

        /** PUTS */
        $app->put(
            '/configurations/{configuration}',
            function($configuration) use ($app, $request) {
                if($configuration === null) {
                    return new JsonResponse('Configuration was not Found', Response::HTTP_NOT_FOUND);
                }

                $data = json_decode($request->getContent());

                $args = [
                    'configuration' => $configuration,
                    'type' => $data->type,
                    'key' => $data->key,
                    'value' => $data->value,
                    'files' => $data->files
                ];

                $action = new PutConfigurationAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configuration', 'converter.configuration:convert');

        $app->put(
            '/configurationTypes/{configurationType}',
            function($configurationType) use($app, $request) {
                if($configurationType === null) {
                    return new JsonResponse('Configuration Type was not Found', Response::HTTP_NOT_FOUND);
                }

                $data = json_decode($request->getContent());

                $args = [
                    'configurationType' => $configurationType,
                    'type' => $data->type
                ];

//                if(property_exists($data, 'configurations')) {
//                    $args['configurations'] = $data->configurations;
//                }

                $action = new PutConfigurationTypeAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationType', 'converter.configuration.type:convert');

        $app->put(
            '/configurationFiles/{configurationFile}',
            function($configurationFile) use ($app, $request) {
                if($configurationFile === null) {
                    return new JsonResponse('Configuration File was not Found', Response::HTTP_NOT_FOUND);
                }
                $data = json_decode($request->getContent());

                $args = [
                    'configurationFile' => $configurationFile,
                    'path' => $data->path
                ];

                if(property_exists($data, 'configurations')) {
                    $args['configurations'] = $data->configurations;
                }

                $action = new PutConfigurationFileAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationFile', 'converter.configuration.file:convert');

        /** PATCHES */

        $app->patch(
            '/configurations/{configuration}',
            function($configuration) use ($app, $request) {
                $args['configuration'] = $configuration;

                $requestBody = $request->getContent();
                $requestJson = json_decode($requestBody);

                if(property_exists($requestJson, 'type')) {
                    $args['type'] = $requestJson->type;
                }

                if(property_exists($requestJson, 'key')) {
                    $args['key'] = $requestJson->key;
                };

                if(property_exists($requestJson, 'value')) {
                    $args['value'] = $requestJson->value;
                };

                if(property_exists($requestJson, 'files')) {
                    $args['files'] = $requestJson->files;
                }

                $action = new PatchConfigurationAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configuration', 'converter.configuration:convert');

        $app->patch(
            '/configurationTypes/{configurationType}',
            function($configurationType) use($app, $request) {
                $requestBody = $request->getContent();
                $requestJson = json_decode($requestBody);

                $args = [
                    'configurationType' => $configurationType,
                    'type' => $requestJson->type,
                    'configurations' => $requestJson->configurations
                ];

                $action = new PatchConfigurationTypeAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationType', 'converter.configuration.type:convert');

        $app->patch(
            '/configurationFiles/{configurationFile}',
            function($configurationFile) use ($app, $request) {

                $requestBody = $request->getContent();
                $requestJson = json_decode($requestBody);

                $args = [
                    'configurationFile' => $configurationFile,
                    'path' => $requestJson->path,
                    'configurations' => $requestJson->configurations
                ];

                $action = new PatchConfigurationFileAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationFile', 'converter.configuration.file:convert');

        /** DELETES */
        $app->delete(
            '/configurations/{configuration}',
            function($configuration) use ($app, $request) {
                if($configuration === null) {
                    return new JsonResponse('Configuration was not Found', Response::HTTP_NOT_FOUND);
                }

                $args = [
                    'configuration' => $configuration
                ];

                $action = new DeleteConfigurationAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configuration', 'converter.configuration:convert');

        $app->delete(
            '/configurationTypes/{configurationType}',
            function($configurationType) use($app, $request) {
                if($configurationType === null) {
                    return new JsonResponse('Configuration Type was not Found', Response::HTTP_NOT_FOUND);
                }

                $args = [
                    'configurationType' => $configurationType
                ];

                $action = new DeleteConfigurationTypeAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationType', 'converter.configuration.type:convert');

        $app->delete(
            '/configurationFiles/{configurationFile}',
            function($configurationFile) use ($app, $request) {
                if($configurationFile === null) {
                    return new JsonResponse('Configuration File was not Found', Response::HTTP_NOT_FOUND);
                }

                $args = [
                    'configurationFile' => $configurationFile
                ];

                $action = new DeleteConfigurationFileAction($app, $request, $args);
                return $action->execute();
            }
        )->convert('configurationFile', 'converter.configuration.file:convert');

        return $app->handle($request);
    }

    /**
     * @param Application $app
     */
    private function initConverters(Application $app)
    {
        $app['converter.configuration'] = $app->share(function () {
            return new ConfigurationConverter();
        });

        $app['converter.configuration.file'] = $app->share(function () {
            return new ConfigurationFileConverter();
        });

        $app['converter.configuration.type'] = $app->share(function () {
            return new ConfigurationTypeConverter();
        });
    }

}