<?php

namespace Ylab\Meetings\Repository;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\ORM;

/**
 * Class MeetingRepository
 * @package Ylab\Meetings\Repository
 */
class MeetingRepository implements RepositoryInterface
{

    /** @var string $hlblock_id ID HL блока */
    private string $hlblock_id;
    /**
     * @var ORM\Data\DataManager|string
     */
    private $entityDataClass;

    /**
     * MeetingRepository constructor.
     * @param string $hlblock_id
     */
    public function __construct(string $hlblock_id)
    {
        $this->hlblock_id = $hlblock_id;

        if (Loader::includeModule('highloadblock')) {

            $this->entityDataClass = $this->getEntity();
        }
    }

    /**
     * @return ORM\Data\DataManager|string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getEntity()
    {
        $hlblock = HL\HighloadBlockTable::getById($this->hlblock_id)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);

        $entityDataClass = $entity->getDataClass();

        return $entityDataClass;
    }

    /**
     * Получение элемента по ID и параметру 'select'
     *
     * @param $id
     * @param $select
     * @return array|false|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function fetchOne($id, $select)
    {
        return $this->entityDataClass::getByPrimary($id, array('select' => $select))->fetch();
    }

    /**
     * Метод возвращает количество записей для конкретного запроса
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
        return $this->entityDataClass::GetList([
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
     * @return int|mixed
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getCount($filter)
    {
        return $this->entityDataClass::getCount($filter);
    }

    /**
     * Добавление элемента
     *
     * @param $filter
     * @param $fields
     * @return ORM\Data\AddResult|mixed
     * @throws \Exception
     */
    public function add($filter, $fields)
    {
        return $this->entityDataClass::add($fields);
    }

    /**
     * Редактирование элемента
     *
     * @param $id
     * @param $fields
     * @return ORM\Data\UpdateResult|mixed
     * @throws \Exception
     */
    public function update($id, $fields)
    {
        $res = $this->entityDataClass::update($id, $fields);
        $this->clearCache($id);
        return $res;
    }

    /**
     * Удаление элемента
     *
     * @param $id
     * @return ORM\Data\DeleteResult|mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        if (is_array($id)) {
            foreach ($id as $item)
                $result = $this->entityDataClass::delete($item);
        } else {
            $result = $this->entityDataClass::delete($id);
        }

        $this->clearCache($id);

        return $result;
    }

    /**
     * Очистка кэша
     *
     * @param $id
     * @return mixed|void
     */
    public function clearCache($id)
    {
        $tableName = "orm_". $this->entityDataClass::getTableName();
        $managedcache = Application::getInstance()->getManagedCache();
        $managedcache->clean($id, $tableName);
    }

    public function getEnumIdByXmlId($field, $xmlId) {

    }

    public function getEnumXmlIdById($field, $id) {

    }
}