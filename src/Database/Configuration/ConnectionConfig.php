<?php

declare(strict_types=1);

namespace ApiCore\Database\Configuration;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;

#[Configuration]
class ConnectionConfig
{
    private string $url;

    private string $user;

    private string $password;

    private string $name;

    public function getUrl(): string
    {
        return $this->url;
    }
    
    #[Value('database.url', '127.0.0.1')]
    public function setUrl(string $url): ConnectionConfig
    {
        $this->url = $url;
        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }
    
    #[Value('database.user', 'root')]
    public function setUser(string $user): ConnectionConfig
    {
        $this->user = $user;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    
    #[Value('database.password', '')]
    public function setPassword(string $password): ConnectionConfig
    {
        $this->password = $password;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
    #[Value('database.name', '')]
    public function setName(string $name): ConnectionConfig
    {
        $this->name = $name;
        return $this;
    }
}
