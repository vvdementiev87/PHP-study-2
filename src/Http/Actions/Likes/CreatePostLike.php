<?php

namespace devavi\leveltwo\Http\Actions\Likes;

use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Blog\Exceptions\InvalidArgumentException;
use devavi\leveltwo\Blog\Exceptions\LikeAlreadyExists;
use devavi\leveltwo\Blog\Exceptions\PostNotFoundException;
use devavi\leveltwo\Blog\Like;
use devavi\leveltwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use devavi\leveltwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use devavi\leveltwo\Blog\UUID;
use devavi\leveltwo\Http\Actions\ActionInterface;
use devavi\leveltwo\Http\ErrorResponse;
use devavi\leveltwo\http\Request;
use devavi\leveltwo\http\Response;
use devavi\leveltwo\Http\SuccessfulResponse;

class CreatePostLike implements ActionInterface
{
    public   function __construct(
        private LikesRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postRepository,
    ) {
    }


    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->JsonBodyField('post_uuid');
            $userUuid = $request->JsonBodyField('user_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        //TODO тоже и для юзера добавить
        try {
            $this->postRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        try {
            $this->likesRepository->checkUserLikeForPostExists($postUuid, $userUuid);
        } catch (LikeAlreadyExists $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newLikeUuid = UUID::random();

        $like = new Like(
            uuid: $newLikeUuid,
            post_uuid: new UUID($postUuid),
            user_uuid: new UUID($userUuid),

        );

        $this->likesRepository->save($like);

        return new SuccessFulResponse(
            ['uuid' => (string)$newLikeUuid]
        );
    }
}
