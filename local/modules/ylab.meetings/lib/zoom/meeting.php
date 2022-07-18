<?php

namespace Ylab\Meetings\Zoom;

use GuzzleHttp\Client;

class Meeting
{
    protected Settings $settings;
    protected Auth $auth;

    const BASE_URL = 'https://api.zoom.us';
    const URL_LIST_MEETINGS = '/v2/users/me/meetings';
    const URL_CREATE_MEETING = '/v2/users/me/meetings';
    const URL_BYID_MEETINNG = '/v2/meetings/';
    const URL_DELETE_MEETINNG = '/v2/meetings/';


    public function __construct()
    {
        $this->settings = new Settings();
        $this->auth = new Auth();
    }


    /**
     * @param $meeting_name
     * @param $start_time - формат 2022-07-14T13:30:00
     * @param string $password todo: доделать/протестить
     * @param int $duration
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Создаёт ссылку на конференцию и возвращает её.
     */
    public function create($meeting_name, $start_time, $password = '123456', $duration = 30)
    {
        $client = new Client(['base_uri' => self::BASE_URL]);

        if ($this->auth->hasToken()) {
            $accessToken = $this->auth->getToken();
        } else {
            $accessToken = $this->auth->authorization();
        }

        try {
            $response = $client->request('POST', self::URL_CREATE_MEETING, [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ],
                'json' => [
                    "topic" => $meeting_name,
                    "type" => 2, // тип - запланированная встреча
                    "start_time" => $start_time, // дата и время планируемой встречи
                    "duration" => $duration, // Количество запланированных минут
                    "password" => $password // Пользователь также должен установить пароль встречи
                ],
            ]);

            $data = json_decode($response->getBody());

        } catch (\Exception $e) {
            // если не авторизован или токен просрочен
            if (401 == $e->getCode()) {

                $this->auth->authorization(); // todo: получить новый или refresh_token???
                $this->create($meeting_name, $start_time, $password, $duration);
            } else {
                echo $e->getMessage();
            }
        }

        return $data;
    }


    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Возвращает список конференций
     */
    public function list()
    {
        $client = new Client(['base_uri' => self::BASE_URL]);

        if ($this->auth->hasToken()) {
            $accessToken = $this->auth->getToken();
        } else {
            $accessToken = $this->auth->authorization();
        }

        try {
            $response = $client->request('GET', self::URL_LIST_MEETINGS, [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ]
            ]);
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                $accessToken = $this->auth->authorization();

                $response = $client->request('GET', self::URL_LIST_MEETINGS, [
                    "headers" => [
                        "Authorization" => "Bearer $accessToken"
                    ]
                ]);
            } else {
                echo $e->getMessage();
            }
        }

        $data = json_decode($response->getBody());

        return $data;
    }


    public function getById($id)
    {
        $uri = self::URL_BYID_MEETINNG . $id;

        $client = new Client(['base_uri' => self::BASE_URL]);

        if ($this->auth->hasToken()) {
            $accessToken = $this->auth->getToken();
        } else {
            $accessToken = $this->auth->authorization();
        }

        try {
            $response = $client->request('GET', $uri, [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ]
            ]);
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                $accessToken = $this->auth->authorization();

                $response = $client->request('GET', $uri, [
                    "headers" => [
                        "Authorization" => "Bearer $accessToken"
                    ]
                ]);
            } else {
                echo $e->getMessage();
            }
        }

        $data = json_decode($response->getBody());

        return $data;
    }


    /**
     * @param $id
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Удаляет переговорную по id
     */
    public function delete($id)
    {
        $url = self::URL_DELETE_MEETINNG . $id;
        $client = new Client(['base_uri' => self::BASE_URL]);

        if ($this->auth->hasToken()) {
            $accessToken = $this->auth->getToken();
        } else {
            $accessToken = $this->auth->authorization();
        }

        try {
            $response = $client->request('DELETE', $url, [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ]
            ]);
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                $accessToken = $this->auth->authorization();

                $response = $client->request('DELETE', $url, [
                    "headers" => [
                        "Authorization" => "Bearer $accessToken"
                    ]
                ]);
            } else {
                echo $e->getMessage();
            }
        }

        if (204 == $response->getStatusCode()) {
            echo "Переговорная удалена";
        }
    }
}
