<?php
namespace devavi\leveltwo\User;

class User {
    
    private int $id;
    private string $username;
    private string $login;

    public function __construct(int $id, string $username, string $login)
    {
        $this->id = $id;
        $this->username = $username;
        $this->login = $login;
    }

    public function __toString(): string
    {
        return "Юзер $this->id с именем $this->username и логином $this->login." . PHP_EOL;
    }

}
