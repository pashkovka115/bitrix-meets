<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

class IntegrationTable extends Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'y_meetings_integration';
    }

    public static function getUfId(): string
    {
        return 'INTEGRATION_YLAB';
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
            //Название
            new Entity\StringField('NAME', array(
                'required' => true,
            )),
            //Активность
            new Entity\BooleanField('ACTIVITY', array(
                'required' => true,
            )),
            //Ссылка на класс интеграции
            new Entity\StringField('INTEGRATION_REF', array(
                'required' => true
            )),
            //Логин от интеграции
            new Entity\StringField('LOGIN', array(
                'required' => true
            )),
            //Пароль от интеграции
            new Entity\StringField('PASSWORD', array(
                'required' => true
            )),
            //Обратный референс (для реализации двунаправленности) (отношение "1 интеграция - N комнат")
            (new OneToMany('ROOMS', RoomTable::class, 'INTEGRATION'))
                ->configureJoinType('inner'),
        );
    }
}
