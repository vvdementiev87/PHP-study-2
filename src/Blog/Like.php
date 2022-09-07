<?php

namespace devavi\leveltwo\Blog;

class Like
{
    public function __construct(
        private UUID $uuid,
        private UUID $post_id,
        private UUID $user_id,
    ) {
    }

    /**
     * @return UUID
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UUID
     */
    public function PostId(): UUID
    {
        return $this->post_id;
    }

    /**
     * @param UUID $post_id
     */
    public function setPostId(UUID $post_id): void
    {
        $this->post_id = $post_id;
    }

    /**
     * @return UUID
     */
    public function UserId(): UUID
    {
        return $this->user_id;
    }

    /**
     * @param UUID $user_id
     */
    public function setUserId(UUID $user_id): void
    {
        $this->user_id = $user_id;
    }
}
