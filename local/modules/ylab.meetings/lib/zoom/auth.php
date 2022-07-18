<?php

namespace Ylab\Meetings\Zoom;

use Bitrix\Main\Diag\Debug;
use GuzzleHttp\Client;

class Auth
{
    protected Settings $settings;
    protected $baseUri = 'https://zoom.us';
    protected $moduleId = 'ylab.meetings';
    /**
     * @var string
     * Нужен только для идентификации в БД Битрикса
     */
    private $tokenName = 'zoom.token';


    public function __construct()
    {
        $this->settings = new Settings();
    }


    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Авторизация. Получение нового токена и сохранение/замена старого токена. Возвращает новый токен.
     */
    public function authorization()
    {
        $this->deleteToken();
        $token = $this->getNewToken();
        if ($token) {
            $this->saveToken($token);
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
            $client = new Client(['base_uri' => $this->baseUri]);

            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode(
                            $this->settings->getClientId() . ':' . $this->settings->getClientSecret()
                        )
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $_GET['code'],
                    "redirect_uri" => $this->settings->getRedirectURI()
                ],
            ]);
            $token = json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return isset($token['access_token']) ? $token['access_token'] : false;
    }


    /**
     * @param $token
     * Сохраняет токен в БД
     */
    public function saveToken($token)
    {
        global $DB;
        $DB->Query("INSERT INTO b_option SET NAME='$this->tokenName', MODULE_ID='$this->moduleId', VALUE='$token'");
    }


    /**
     * @return string
     * Возвращает токен из БД
     */
    public function getToken()
    {
        global $DB;
        $row = $DB->Query("SELECT * FROM b_option WHERE NAME='$this->tokenName' AND MODULE_ID='$this->moduleId'")->Fetch();
        return $row['VALUE'];
    }


    /**
     * Удаляет токен в БД
     */
    public function deleteToken()
    {
        global $DB;
        $DB->Query("DELETE FROM b_option WHERE NAME='$this->tokenName' AND MODULE_ID='$this->moduleId'");
    }


    /**
     * @param $new_token - новый токен
     * Обновляет существующий токен в БД
     */
    public function updateToken($new_token)
    {
        global $DB;
        $DB->Query("UPDATE b_option SET VALUE='$new_token' WHERE NAME='$this->tokenName' AND MODULE_ID='$this->moduleId'");
    }


    /**
     * @return bool
     * Проверяет существование токена в БД.
     * На актуальность не проверяет.
     */
    public function hasToken(): bool
    {
        global $DB;
        return (bool)$DB->Query("SELECT * FROM b_option WHERE NAME='$this->tokenName' AND MODULE_ID='$this->moduleId'")->Fetch();
    }
}
