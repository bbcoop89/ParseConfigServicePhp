<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 8:25 PM
 */

namespace Brit\ParseConfigService\Actions;


use Brit\Library\ApplicationSettings;
use Brit\Library\Exceptions\ApplicationSettingNotFoundException;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractAction
 * @package Brit\ParseConfigService\Actions
 */
abstract class AbstractAction
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var EntityManager
     */
    protected $entityManager;


    /**
     * @param Application $app
     * @param Request $request
     * @param array $args
     */
    public function __construct(
        Application $app,
        Request $request,
        array $args = []
    ) {
        $this->app = $app;
        $this->request = $request;
        $this->args = $args;
        $this->entityManager = ApplicationSettings::getSetting('orm.entity.manager');
    }

    /**
     * @return JsonResponse
     */
    abstract public function execute();

    /**
     * @param string $location
     * @param string $content
     *
     * @return JsonResponse
     * @throws \Exception
     * @throws ApplicationSettingNotFoundException
     */
    protected function created($location, $content = '') {
        if(
            !is_string($location)
            || strlen(trim($location)) === 0
        ) {
            throw new \Exception('Invalid HTTP CREATED Location Provided.');
        }

        $response = new JsonResponse($content, Response::HTTP_CREATED);
        $response->headers->set(
            'Location',
            ApplicationSettings::getSetting('baseAppUrl') . $location
        );

        return $response;
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function ok($message = '') {
        return new JsonResponse($message, Response::HTTP_OK);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function forbidden($message = '403 - Forbidden') {
        return new JsonResponse($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function notFound($message = '404 - Not Found') {
        return new JsonResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function unauthorized($message = '401 - Unauthorized') {
        return new JsonResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function unprocessableEntity($message = '422 - Unprocessable Entity') {
        return new JsonResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function internalServerError($message = '500 - Internal Server Error') {
        return new JsonResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param null $message
     *
     * @return JsonResponse
     */
    protected function noContent($message = null) {
        return new JsonResponse($message, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function badRequest($message = '400 - Bad Request') {
        return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $location
     * @param string $content
     *
     * @return JsonResponse
     * @throws \Exception
     * @throws ApplicationSettingNotFoundException
     */
    protected function seeOther($location, $content = '') {
        if(
            !is_string($location)
            || strlen(trim($location)) === 0
        ) {
            throw new \Exception('Invalid HTTP SEE OTHER Location Provided.');
        }

        $response = new JsonResponse($content, Response::HTTP_SEE_OTHER);
        $response->headers->set(
            'Location',
            ApplicationSettings::getSetting('baseAppUrl') . $location
        );

        return $response;
    }
}