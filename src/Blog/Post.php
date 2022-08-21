<?php
namespace devavi\leveltwo\Blog;

class Post {
    
    private int $id;
    private int $userId;
    private string $header;
    private string $text;

    public function __construct(int $id, int $userId, string $header, string $text)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->header = $header;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Юзер $this->userId написал пост номер $this->id с заголовком: $this->header и текстом: $this->text" . PHP_EOL;
    }

}