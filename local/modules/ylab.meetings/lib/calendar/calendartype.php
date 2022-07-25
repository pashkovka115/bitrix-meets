<?php

namespace Ylab\Meetings\Calendar;

use Bitrix\Main\ArgumentException;
use CCalendarType;

/**
 * Class CalendarType
 * @package Ylab\Meetings\Calendar
 * Обёртка по работе с типом календаря
 */
class CalendarType
{
    /**
     * @param array $params
     * @return array|false|string|string[]|void|null
     * @throws ArgumentException
     * Добавляет новый тип календаря
     */
    public static function add(array $params)
    {
        if (!$params['XML_ID'] or !$params['NAME']){
            throw new ArgumentException('Необходимо передать: XML_ID и NAME');
        }

        $xml_id = CCalendarType::Edit(array(
            'NEW' => true, // add
            'arFields' => $params
        ));

        return $xml_id;
    }


    /**
     * @return array|false|void
     * Возвращает список типов календаря
     */
    public static function getAll()
    {
        return CCalendarType::GetList();
    }


    /**
     * @param $xml_id
     * @return false|array
     * Получает тип календаря по XML_ID
     */
    public static function getById(string $xml_id)
    {
        $item = CCalendarType::GetList([
            'arFilter' => ['XML_ID' => $xml_id]
        ]);
        if (count($item) == 1){
            return $item[0];
        }
        return false;
    }


    /**
     * @param string $xml_id
     * @return bool
     * Проверяет существование типа календаря
     */
    public static function hasItem(string $xml_id)
    {
        return (bool)self::getById($xml_id);
    }


    /**
     * @param string $xml_id
     * Удаляет тип календаря
     */
    public static function delete(string $xml_id)
    {
        CCalendarType::Delete($xml_id);
    }


    /**
     * @param $params
     * @return array|false|string|string[]|void|null
     * @throws ArgumentException
     * Обновляет тип календаря
     */
    public static function edit($params)
    {
        if (!$params['XML_ID'] or !$params['NAME']){
            throw new ArgumentException('Необходимо передать: XML_ID и NAME');
        }

        $xml_id = CCalendarType::Edit(array(
            'NEW' => false, // update
            'arFields' => $params
        ));

        return $xml_id;
    }
}
