<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\Reference;

class RoomTable extends Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'y_meetings_room';
    }

    public static function getUfId(): string
    {
        return 'ROOM_YLAB';
    }

    public static function getConnectionName(): string
    {
        return 'default';
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap(): array
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            //Название комнаты
            new Entity\StringField('NAME', array(
                'required' => true,
            )),
            //Активность
            new Entity\BooleanField('ACTIVITY', array(
                'required' => true,
            )),
            //ID интеграции
            new Entity\IntegerField('INTEGRATION_ID', array(
                'required' => true
            )),
            //JOIN на интеграцию
            (new Reference(
                'INTEGRATION',
                IntegrationTable::class,
                Join::on('this.INTEGRATION_ID', 'ref.ID')
            ))
                ->configureJoinType('inner')
        );
    }
}