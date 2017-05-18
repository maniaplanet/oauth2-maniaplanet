<?php

namespace Maniaplanet\OAuth2\Client\Maniaplanet\Entity;

class ManiaplanetUser
{
    private $login;

    private $nickname;

    private $path;

    public function __construct(array $response)
    {
        $this->login = $response['login'];
        $this->nickname = $response['nickname'];
        $this->path = $response['path'];
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


}
