<?php
use App\App;

spl_autoload_register(/**
 * @throws Exception
 */ callback: function ($className) {
    $className = str_replace("\\", "/", $className);
    $className = str_replace("App/", "", $className);

    require_once __DIR__ . "/src/$className.php";
});


$app = new App();

$app->setPath('tasksPatch', './tasks.json');

$app->router->get('/', 'TaskController@index');
$app->router->post('/add', 'TaskController@add');
$app->router->delete('/delete/{id}',  'TaskController@delete');


try {
    $app->router->route();
} catch (ReflectionException $e) {
    echo $e->getMessage();
}