<?php

namespace Ylab\Meetings\Zoom;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Diag\Debug;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Auth
{
    protected $moduleId = 'ylab.meetings';
    protected $clientId;
    protected $clientSecret;
    protected $urlRedirect;
    /**
     * @var string
     * Нужен только для идентификации в БД Битрикса
     */
    private $tokenName = 'zoom.token';

    const BASE_URL = 'https://zoom.us';
    const URL_NEW_TOKEN = '/oauth/token';
    /**
     * Количество попыток авторизации
     */
    const COUNT_AUTH = 3;
    const LOG_FILE = 'local/log/zoom_auth.txt';


    public function __construct()
    {
        $this->clientId = \COption::GetOptionString($this->moduleId, 'client_id');
        $this->clientSecret = \COption::GetOptionString($this->moduleId, 'client_secret');
        $this->urlRedirect = \COption::GetOptionString($this->moduleId, 'zoom_redirect_url');
    }


    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Авторизация. Получение нового токена и сохранение/замена старого токена. Возвращает новый токен.
     */
    public function authorization()
    {
        static $num = 1;

        $token = $this->getNewToken();
        if ($token && $token != 'error') {
            $this->deleteToken();
            $this->saveToken($token);
        }else{
            if ($num >= self::COUNT_AUTH){
                exit();
            }
            Debug::writeToFile('Ошибка авторизации', 'Auth', self::LOG_FILE);
            $num++;
            $this->authorization();
        }

        return $token;
    }


    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Получает новый токен из сервиса Zoom
     * [
        'access_token' => 'jiuyoui...',
        'token_type' => 'bearer',
        'refresh_token' => 'jiuyoui...',
        'expires_in' => 3599,
        'scope' => 'meeting:write:admin'
        ];
     */
    public function getNewToken()
    {
        try {
            $client = new Client(['base_uri' => self::BASE_URL]);

            $response = $client->request('POST', self::URL_NEW_TOKEN, [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode(
                            $this->clientId . ':' . $this->clientSecret
                        )
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $_GET['code'],
                    "redirect_uri" => $this->urlRedirect
                ],
                'connect_timeout' => 5
            ]);

            $token = json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception = (string) $e->getResponse()->getBody();
                $exception = json_decode($exception);
                Debug::writeToFile(json_encode($exception), 'exception_error', self::LOG_FILE);
            } else {
                Debug::writeToFile('Ошибка: ' . http_response_code(503), 'exception_503', self::LOG_FILE);
            }
        }

        if (isset($token['access_token'])){
            $result = $token['access_token'];
        }elseif (is_string($token)){
            $result = 'error';
            Debug::writeToFile($token, 'error_token', self::LOG_FILE);
        }else{
            $result = (bool)$token;
        }

        return $result;
    }


    /**
     * @param $token
     * Сохраняет токен в БД
     */
    public function saveToken($token)
    {
        Option::set($this->moduleId, $this->tokenName, $token);
    }


    /**
     * @return string
     * Возвращает токен из БД
     */
    public function getToken()
    {
        return Option::get($this->moduleId, $this->tokenName);
    }


    /**
     * Удаляет токен в БД
     */
    public function deleteToken()
    {
        Option::delete($this->moduleId, ['name' => $this->tokenName]);
    }


    /**
     * @param $new_token - новый токен
     * Обновляет существующий токен в БД
     */
    public function updateToken($new_token)
    {
        Option::set($this->moduleId, $this->tokenName, $new_token);
    }


    /**
     * @return bool
     * Проверяет существование токена в БД.
     * На актуальность не проверяет.
     */
    public function hasToken(): bool
    {
        return (bool)Option::get($this->moduleId, $this->tokenName, false);
    }
}
