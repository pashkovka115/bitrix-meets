<?php

namespace Ylab\Meetings\Repository;

use Bitrix\Main\Application;
use Ylab\Meetings\Orm\IntegrationTable;
use Bitrix\Main\ORM;


/**
 *  Класс для работы с IntegrationTable
 *
 * Class RoomRepository
 * @package Ylab\Meetings\Repository
 */
class IntegrationRepository extends BaseRepository
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
        return IntegrationTable::getByPrimary($id, array('select' => $select))->fetch();
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

        return IntegrationTable::GetList([
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
        return IntegrationTable::getCount($filter);
    }


    /**
     * Добавление интеграции
     *
     * @param $filter
     * @param $fields
     * @return ORM\Data\AddResult|mixed
     * @throws \Exception
     */
    public function add($filter, $fields)
    {
        return IntegrationTable::add($fields);
    }


    /**
     * Удаление интеграции
     *
     * @param $id
     * @param $fields
     * @return ORM\Data\UpdateResult|mixed
     * @throws \Exception
     */
    public function update($id, $fields)
    {
        $res = IntegrationTable::update($id, $fields);
        $this->clearCache();
        return $res;
    }


    /**
     * Редактирование интеграции
     *
     * @param $id
     * @return ORM\Data\DeleteResult|ORM\Data\UpdateResult|mixed
     * @throws \Exception
     */
    public function delete($id)
    {

        /** @var \Bitrix\Main\ORM\Data\UpdateResult $result */
        if (is_array($id)) {
            foreach ($id as $item)
                $result = IntegrationTable::delete($item);
        } else {
            $result = IntegrationTable::delete($id);
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
        $tableName = "orm_". IntegrationTable::getTableName();
        $managedcache = Application::getInstance()->getManagedCache();
        $managedcache->cleanDir($tableName);
    }


}