<?php

namespace devavi\leveltwo\Http\Actions\Posts;

use devavi\leveltwo\Blog\Post;
use devavi\leveltwo\Blog\UUID;
use devavi\leveltwo\Http\Request;
use devavi\leveltwo\Http\Response;
use devavi\leveltwo\Http\ErrorResponse;
use devavi\leveltwo\http\SuccessfulResponse;
use devavi\leveltwo\Http\Actions\ActionInterface;
use devavi\leveltwo\Blog\Exceptions\AuthException;
use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Blog\Exceptions\UserNotFoundException;
use devavi\leveltwo\Blog\Exceptions\InvalidArgumentException;
use devavi\leveltwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use devavi\leveltwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class CreatePost implements ActionInterface
{
    // Внедряем репозитории статей и пользователей
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    ) {
    }
    public function handle(Request $request): Response
    {

        try {
            $user = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }


        $newPostUuid = UUID::random();

        try {
            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->postsRepository->save($post);
        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
