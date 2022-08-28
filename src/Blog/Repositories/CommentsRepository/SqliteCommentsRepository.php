<?php

namespace devavi\leveltwo\Blog\Repositories\CommentsRepository;

use devavi\leveltwo\Blog\Exceptions\InvalidArgumentException;
use devavi\leveltwo\Blog\Exceptions\CommentNotFoundException;
use devavi\leveltwo\Blog\Comment;
use devavi\leveltwo\Blog\UUID;
use \PDO;
use \PDOStatement;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text) 
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'

        );
   
        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->post_uuid(),
            ':author_uuid' => (string)$comment->author_uuid(),
            ':text' => $comment->text(),
        ]);

    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );

        $statement->execute([(string)$uuid]);
       
        return $this->getComment($statement, $uuid);
    }

    private function getComment(PDOStatement $statement, string $errorString): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find comment: $errorString"
            );
        }

        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['author_uuid']),
            $result['text']
        );
    }
}