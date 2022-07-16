<?php

namespace Ylab\Meeting\Zoom;

class Settings
{
    /**
     * @return string
     * ID клиента из аккаунта zoom
     */
    public function getClientId(): string
    {
        return 'Hvzoxl5vR3y7gMkp6c8G8A';
    }


    /**
     * @return string
     * Секретный код из аккаунта zoom
     */
    public function getClientSecret(): string
    {
        return 'YufGNw2htdIT33tleENAWD0BOtj8wec4';
    }


    /**
     * @return string
     * Возвращает адрес для перенаправления
     * В период разработки может возвращать виртуальный адрес ***.ngrok.io, в этом случае требуется установка ngrok
     * https://ngrok.com/
     */
    public function getRedirectURI(): string
    {
        return 'https://f9d7-45-157-215-252.eu.ngrok.io/oauth-zoom-ylab/authorize';
    }
}
