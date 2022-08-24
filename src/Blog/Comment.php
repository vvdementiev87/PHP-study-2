<?php
namespace devavi\leveltwo\Blog;

class Comment {
    
    private int $id;
    private int $userId;
    private int $postId;
    private string $text;

    public function __construct(int $id, int $userId, int $postId, string $text)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->postId = $postId;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Юзер $this->userId написал коментарий к посту номер $this->postId с номером $this->id и текстом: $this->text" . PHP_EOL;
    }

}