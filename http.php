<?php


use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use devavi\leveltwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use devavi\leveltwo\Http\Actions\Users\CreateUser;
use devavi\leveltwo\Http\Actions\Users\FindByUsername;
use devavi\leveltwo\Http\ErrorResponse;
use devavi\leveltwo\Http\Request;
use devavi\leveltwo\Http\SuccessfulResponse;
use devavi\leveltwo\Http\Actions\Posts\FindByUuid;
use devavi\leveltwo\Http\Actions\Posts\CreatePost;
use devavi\leveltwo\Http\Actions\Posts\DeletePost;
use devavi\leveltwo\Http\Actions\Comments\CreateComment;
use devavi\leveltwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'),);

$routes = [
    'GET' => [
        //http://localhost:80/users/show?username=Den

        '/users/show' => new FindByUsername(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        //http://localhost:80/posts/show?uuid=cd3e7bf6-4cf8-4460-a7c3-0ba1836ceabd
        '/posts/show' => new FindByUuid(
            new SqlitePostsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
            ),
            
    ],
    'POST' => [
        '/users/create' => new CreateUser(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/create' => new CreatePost(
            new SqlitePostsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/comment' => new CreateComment(
            new SqliteCommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
                ),
            new SqlitePostsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
            
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new SqlitePostsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
            ),
            
    ],

];


try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
// Пытаемся получить HTTP-метод запроса
    $method = $request->method();
} catch (HttpException) {
// Возвращаем неудачный ответ,
// если по какой-то причине
// не можем получить метод
    (new ErrorResponse)->send();
    return;
}

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
