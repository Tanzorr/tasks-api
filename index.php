<?php


use App\Core\App;

require_once __DIR__."/vendor/autoload.php";

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