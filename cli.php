<?php

use devavi\leveltwo\Blog\Command\Arguments;
use devavi\leveltwo\Blog\Command\CreateUserCommand;
use devavi\leveltwo\Blog\Command\CreatePostCommand;
use devavi\leveltwo\Blog\Command\CreateCommentCommand;
use devavi\leveltwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use devavi\leveltwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use devavi\leveltwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use devavi\leveltwo\Blog\UUID;

include __DIR__ . "/vendor/autoload.php";

//Создаём объект подключения к SQLite
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$postsRepository = new SqlitePostsRepository($connection);
var_dump($postsRepository->get(new UUID('cd3e7bf6-4cf8-4460-a7c3-0ba1836ceabd')));

die();

$route = $argv[1];

switch ($route) {
    case "user":
        $usersRepository = new SqliteUsersRepository($connection);
        $command = new CreateUserCommand($usersRepository);
        
        try {
            $command->handle(Arguments::fromArgv($argv));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case "post":
        $postsRepository = new SqlitePostsRepository($connection);
        $usersRepository = new SqliteUsersRepository($connection);
        $command = new CreatePostCommand($postsRepository, $usersRepository);

        try {
            $command->handle(Arguments::fromArgv($argv));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;

    case "comment":
        $commentsRepository = new SqliteCommentsRepository($connection);
        $command = new CreateCommentCommand($commentsRepository);
        try {
            $command->handle(Arguments::fromArgv($argv));
        } catch (Exception $e) {
            echo $e->getMessage();
        } 
        break;

    default:
        echo "error try user post comment parameter";
}



