<?php
require __DIR__.'/vendor/autoload.php';
use BulveyzRouter\Route;
use BulveyzRouter\Router;
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use App\Controllers\HomeController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

date_default_timezone_set("America/Lima");

CacheManager::setDefaultConfig(new ConfigurationOption([
    'path' => __DIR__.'/storage/xcache',
]));
Route::get('/', 'Controllers\HomeController@index');
Route::post('/get', 'HomeController@get');
Router::routeVoid();
