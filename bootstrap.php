<?php

use Brit\Library\ApplicationSettings;
use Brit\ParseConfigService\Controllers\ParseConfigServiceController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/vendor/autoload.php');

ini_set('error_log', __DIR__ . '/../logs/error.log');

error_reporting(E_ALL);
//$appName = explode('/', $_SERVER["REQUEST_URI"])[1];
//session_set_cookie_params(0, '/' . $appName);
//session_name($appName);
//session_start();

ApplicationSettings::load();

$paths = array(__DIR__ . "..\\Entity");
$isDevMode = false;

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'parse_config_s',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$config->setAutoGenerateProxyClasses(true);

$entityManager = EntityManager::create($dbParams, $config);

ApplicationSettings::setEntityManager($entityManager);



$request = Request::createFromGlobals();

$controller = new ParseConfigServiceController();

$response = $controller->dispatch($request);

$response->send();