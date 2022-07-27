<?php

namespace Ylab\Meetings\Integrations;

use Bitrix\Main\Diag\Debug;
use GuzzleHttp\Client;
use Ylab\Meetings\Zoom\Meeting;

class Zoom extends IntegrationBase
{
    const BASE_URL = 'https://zoom.us';
    protected $roomName = 'Название встречи';
    protected $startTime;
    protected $password = '123456';
    protected $duration = 30;
    protected $moduleId = 'ylab.meetings';
//    protected $timezone = 'Europe/Moscow';

    public function __construct()
    {
        /*
         * Если время не назначено то через 30 минут.
         */
        $minutes = 30;
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $date->add(new \DateInterval('PT' . $minutes . 'M'));
        $this->startTime = $date->format('Y-m-d H:i:s');
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \stdClass
     * Создаёт конференцию и возвращает данные о ней.
     *
     * start_url - Ссылка на комнату для владельца Zoom аккаунта
     * join_url - Ссылка для присоединения к встречи
     * password - пароль для присоединения
     * start_time - начало встречи
     * timezone
     * topic - название встречи
     */
    public function getLink()
    {
        $meeting = new Meeting();
        return $meeting->create($this->roomName, $this->startTime, $this->password, $this->duration);
    }


    /**
     * @param string $roomName
     * Название встречи
     */
    public function setRoomName(string $roomName): void
    {
        $this->roomName = $roomName;
    }


    /**
     * @param string $startTime
     * Дата и время начала встречи
     * формат 2022-07-14T13:30:00
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }


    /**
     * @param string $password
     * Пароль для встречи
     * todo: эксперементальная функция
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    /**
     * @param int $duration
     * Предпологаемая продолжительность встречи в минутах
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
}
