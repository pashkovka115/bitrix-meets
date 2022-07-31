<?php

namespace Ylab\Meetings\Repository;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM;
use CUserFieldEnum;
use Exception;

/**
 * Class MeetingRepository
 * @package Ylab\Meetings\Repository
 */
class MeetingRepository implements RepositoryInterface
{

    /** @var string $hlblock_id ID HL блока */
    private string $hlblock_id;
    /** @var ORM\Data\DataManager|string Класс сущности HL блока */
    private $entityDataClass;

    /**
     * MeetingRepository constructor.
     * @param string $hlblock_id
     */
    public function __construct(string $hlblock_id)
    {

        if (Loader::includeModule('highloadblock')) {

            $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

            if (!empty($hlblock)) {

                $this->hlblock_id = $hlblock_id;
                $this->entityDataClass = $this->getEntityDataClass();

            } else {
                throw new Exception(Loc::getMessage('YLAB_MEETINGREPOSITORY_ERROR1'));
            }

        }
    }

    /**
     * Возвращает название класса сущности
     *
     * @return ORM\Data\DataManager|string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getEntityDataClass()
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
        $this->clearCache();
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

        $this->clearCache();

        return $result;
    }


    /**
     *  Очистка кэша
     *
     * @return mixed|void
     */
    public function clearCache()
    {
        $tableName = "orm_" . $this->entityDataClass::getTableName();
        $managedcache = Application::getInstance()->getManagedCache();
        $managedcache->cleanDir($tableName);
    }


    /**
     * Метод возвращает установленный EnumXmlId для пользовательского поля типа список
     *
     * @param $field - Код поля (string)
     * @param $id - ID записи HL блока
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getEnumXmlIdById($field, $id)
    {

        $hlblock = HL\HighloadBlockTable::getById($this->hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);

        $fields = [];
        $enumXmlId = null;

        foreach ($entity->getFields() as $fld) {
            $fields[] = $fld->getName();
        }

        if (in_array($field, $fields)) {
            $result = $this->fetchAll(array("ID" => $id), array($field => $field), array(), array(), array());
        }

        if ($result) {
            $UserField = CUserFieldEnum::GetList(array(), array("ID" => $result[0][$field]));
            $enumXmlId = $UserField->arResult[0]["XML_ID"];
        }

        return $enumXmlId;

    }


    /**
     * Метод-хелпер, возвращает значение XML_ID
     * элемента поля типа список по ID элемента списка
     *
     * @param $field - Код поля (string)
     * @param $enum_id - ID элемента списка
     * @return mixed
     */
    public function getEnumXmlIdByEnumId($field, $enum_id)
    {
        global $USER_FIELD_MANAGER;
        $arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_" . $this->hlblock_id);

        $field_id = $arFields[$field]["ID"];

        $obEnum = new CUserFieldEnum;
        $rsEnum = $obEnum->GetList(array(), array("ID" => $enum_id, "USER_FIELD_ID" => $field_id,));

        return $rsEnum->arResult[0]["XML_ID"];
    }


    /**
     * Метод-хелпер, возвращает значение ID
     * элемента поля типа список по XML_ID элемента списка
     *
     * @param $field - Код поля (string)
     * @param $enumXmlId - XML_ID элемента списка
     * @return mixed
     */
    public function getEnumIdByEnumXmlId($field, $enumXmlId)
    {
        global $USER_FIELD_MANAGER;
        $arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_" . $this->hlblock_id);

        $field_id = $arFields[$field]["ID"];

        $obEnum = new CUserFieldEnum;
        $rsEnum = $obEnum->GetList(array(), array("XML_ID" => $enumXmlId, "USER_FIELD_ID" => $field_id,));

        return $rsEnum->arResult[0]["ID"];
    }


}