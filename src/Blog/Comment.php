<?php
namespace devavi\leveltwo\Blog;

class Comment {
    
    private UUID $uuid;
    private UUID $post_uuid;
    private UUID $author_uuid;
    private string $text;

    public function __construct(UUID $uuid, UUID $post_uuid, UUID $author_uuid, string $text)
    {
        $this->uuid = $uuid;
        $this->post_uuid = $post_uuid;
        $this->author_uuid = $author_uuid;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return "Юзер $this->author_uuid написал коментарий к посту номер $this->post_uuid с номером $this->uuid и текстом: $this->text" . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function post_uuid(): UUID
    {
        return $this->post_uuid;
    }

    public function author_uuid(): UUID
    {
        return $this->author_uuid;
    }
    
    public function text(): string
    {
        return $this->text;
    }

}