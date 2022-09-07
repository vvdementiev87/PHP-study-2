<?php

use devavi\leveltwo\Blog\Container\DIContainer;
use devavi\leveltwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use devavi\leveltwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use devavi\leveltwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use devavi\leveltwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use devavi\leveltwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use devavi\leveltwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    LikesRepositoryInterface::class,
    SqliteLikesRepository::class
);

return $container;
