<?php

namespace devavi\leveltwo\Http\Actions\Posts;

use devavi\leveltwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use devavi\leveltwo\Http\Actions\ActionInterface;
use devavi\leveltwo\http\Response;
use devavi\leveltwo\http\ErrorResponse;
use devavi\leveltwo\http\Request;
use devavi\leveltwo\Blog\Exceptions\HttpException;
use devavi\leveltwo\Blog\UUID;
use devavi\leveltwo\Blog\Exceptions\PostNotFoundException;
use devavi\leveltwo\http\SuccessfulResponse;



class FindByUuid implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {
    }



    public function handle(Request $request): Response
    {
        try {
        // Пытаемся получить искомое имя пользователя из запроса
            $postUuid = $request->query('uuid');
        } catch (HttpException $e) {
        // Если в запросе нет параметра username -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }


        try {
    // Пытаемся найти пользователя в репозитории
            $post = $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
    // Если пользователь не найден -
    // возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }


    // Возвращаем успешный ответ
        return new SuccessfulResponse([
            'uuid' => $post->uuid(),
            'post' => "user: ". $post->user()->username() . ', title: ' . $post->title() . ', text: ' . $post->text(),
        ]);
    }
}