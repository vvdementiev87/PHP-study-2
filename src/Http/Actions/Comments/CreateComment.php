<?php

namespace devavi\leveltwo\Http\Actions\Comments;

use devavi\leveltwo\Blog\Exceptions\InvalidArgumentException;
use devavi\leveltwo\Http\Actions\ActionInterface;
use devavi\leveltwo\Http\ErrorResponse;
use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Http\Request;
use devavi\leveltwo\Http\Response;
use devavi\leveltwo\http\SuccessfulResponse;
use devavi\leveltwo\Blog\Comment;
use devavi\leveltwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use devavi\leveltwo\Blog\Exceptions\UserNotFoundException;
use devavi\leveltwo\Blog\Exceptions\PostNotFoundException;
use devavi\leveltwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use devavi\leveltwo\Blog\UUID;
use devavi\leveltwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;

class CreateComment implements ActionInterface
{
public function __construct(
private CommentsRepositoryInterface $commentsRepository,
private PostsRepositoryInterface $postsRepository,
private UsersRepositoryInterface $usersRepository,
) {
}
public function handle(Request $request): Response
{
try {
$authorUuid = new UUID($request->jsonBodyField('author_uuid'));
} catch (HttpException | InvalidArgumentException $e) {
return new ErrorResponse($e->getMessage());
}
try {
$user = $this->usersRepository->get($authorUuid);
} catch (UserNotFoundException $e) {
return new ErrorResponse($e->getMessage());
}

try {
    $postUuid = new UUID($request->jsonBodyField('post_uuid'));
    } catch (HttpException | InvalidArgumentException $e) {
    return new ErrorResponse($e->getMessage());
    }
  
    try {
    $post = $this->postsRepository->get($postUuid);
    } catch (PostNotFoundException $e) {
    return new ErrorResponse($e->getMessage());
    }

$newCommentUuid = UUID::random();
try {
$comment = new Comment(
$newCommentUuid,
$user,
$post,
$request->jsonBodyField('text'),
);
} catch (HttpException $e) {
return new ErrorResponse($e->getMessage());
}
$this->commentsRepository->save($comment);
return new SuccessfulResponse([
'uuid' => (string)$newCommentUuid,
]);
}
}