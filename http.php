<?php


use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Blog\Exceptions\AppException;
use devavi\leveltwo\Http\Actions\Users\CreateUser;
use devavi\leveltwo\Http\Actions\Users\FindByUsername;
use devavi\leveltwo\Http\ErrorResponse;
use devavi\leveltwo\Http\Request;
use devavi\leveltwo\Http\Actions\Posts\FindByUuid;
use devavi\leveltwo\Http\Actions\Posts\CreatePost;
use devavi\leveltwo\Http\Actions\Posts\DeletePost;
use devavi\leveltwo\Http\Actions\Likes\CreatePostLike;
use devavi\leveltwo\Http\Actions\Comments\CreateComment;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        //http://localhost:80/users/show?username=Den
        '/users/show' => FindByUsername::class,

        //http://localhost:80/posts/show?uuid=cd3e7bf6-4cf8-4460-a7c3-0ba1836ceabd
        '/posts/show' => FindByUuid::class,

    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/likes/create' => CreatePostLike::class,
        //{"user_uuid": "e6602945-4049-49b2-bb0b-728a751d6455", "post_uuid": "c29391e3-df21-4201-9282-d3543613da57"}

    ],
    'DELETE' => [
        '/posts' => DeletePost::class,

    ],

];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();
