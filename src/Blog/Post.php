<?php
namespace devavi\leveltwo\Blog;

class Post {
    
    private UUID $uuid;
    private UUID $author_uuid;
    private string $title;
    private string $text;

    public function __construct(UUID $uuid, UUID $author_uuid, string $title, string $text)
    {
        $this->uuid = $uuid;
        $this->author_uuid = $author_uuid;
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Юзер $this->author_uuid написал пост номер $this->uuid с заголовком: $this->title и текстом: $this->text" . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function author_uuid(): UUID
    {
        return $this->author_uuid;
    }

    public function title(): string
    {
        return $this->title;
    }
    
    public function text(): string
    {
        return $this->text;
    }

}