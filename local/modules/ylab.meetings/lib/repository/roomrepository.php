<?php

namespace Ylab\Meetings\Repository;

use Bitrix\Main\Application;
use Ylab\Meetings\RoomTable;
use Bitrix\Main\ORM;


/**
 *  Класс для работы с RoomTable
 *
 * Class RoomRepository
 * @package Ylab\Meetings\Repository
 */
class RoomRepository extends BaseRepository
{

    /**
     *  Получение элемента по ID и параметру 'select'
     *
     * @param $id
     * @param $select
     * @return ORM\Objectify\EntityObject|mixed|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function fetchOne($id, $select)
    {
        return RoomTable::getByPrimary($id, array('select' => $select))->fetch();
    }


    /**
     * Медод возвращает массив с выборкой по передаваемым параметрам
     *
     * @param $filter
     * @param $select
     * @param $order
     * @param $offset
     * @param $limit
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function fetchAll($filter, $select, $order, $offset, $limit)
    {

        return RoomTable::GetList([
          'filter' => $filter,
          "count_total" => true,
          'select' => $select,
          'order' => $order,
          "offset" => $offset,
          "limit" => $limit,
          'cache' => array(
            'ttl' => 3600,
            'cache_joins' => true,
          )
        ])->fetchAll();
    }


    /**
     * Метод возвращает количество записей для конкретного запроса
     *
     * @param $filter
     * @return int
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getCount($filter): int
    {
        return RoomTable::getCount($filter);
    }


    /**
     *  Добавление комнаты
     *
     * @param $filter
     * @param $fields
     * @return ORM\Data\AddResult
     * @throws \Exception
     */
    public function add($filter, $fields): \Bitrix\Main\ORM\Data\AddResult
    {
        return RoomTable::add($fields);
    }


    /**
     * Редактирование комнаты
     *
     * @param $id
     * @param $fields
     * @return ORM\Data\UpdateResult
     * @throws \Exception
     */
    public function update($id, $fields): \Bitrix\Main\ORM\Data\UpdateResult
    {
        $res = RoomTable::update($id, $fields);
        $this->clearCache();
        return $res;
    }


    /**
     * Удаление комнаты
     *
     * @param $id
     * @return ORM\Data\DeleteResult|ORM\Data\UpdateResult|mixed
     * @throws \Exception
     */
    public function delete($id)
    {

        if (is_array($id)) {
            foreach ($id as $item)
                $result = RoomTable::delete($item);
        } else {
            $result = RoomTable::delete($id);
        }

        $this->clearCache();

        return $result;
    }


    /**
     * Очистка кэша
     *
     * @return mixed|void
     */
    public function clearCache()
    {
        $tableName = "orm_". RoomTable::getTableName();
        $managedcache = Application::getInstance()->getManagedCache();
        $managedcache->cleanDir($tableName);
    }


}