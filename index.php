<?php


use App\Container;
use App\Router;

echo "root";
spl_autoload_register(/**
 * @throws Exception
 */ callback: function ($className) {
    $className = str_replace("\\", "/", $className);
    $className = str_replace("App/", "", $className);

    require_once __DIR__ . "/src/$className.php";
});


$container = new Container();
$router = new Router($container);


$container->setParameter('tasksPatch', './tasks.json');

$router->get('/', 'TaskController@index');
$router->post('/add', 'TaskController@add');
$router->delete('/delete/{id}', 'TaskController@delete');


try {
    $router->route();
} catch (ReflectionException $e) {
    echo $e->getMessage();
}