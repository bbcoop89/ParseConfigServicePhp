<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 7:18 PM
 */

namespace Brit\ParseConfigService\ErrorHandlers;


use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParseConfigErrorHandler
 * @package Brit\ParseConfigService\ErrorHandlers
 */
class ParseConfigErrorHandler
{
    /**
     * @param Request $request
     * @param Application $application
     */
    public function handle(Request $request, Application $application)
    {
        $application->error(function (\Exception $e) use ($application, $request) {
            switch (true) {
                default:
                    $eMessage = $e->getMessage();
                    $eCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    error_log($e);
            }

            return new JsonResponse($eMessage, $eCode);
        });
    }
}