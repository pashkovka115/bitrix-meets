<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Localization\Loc;

/**
 * Class for ORM Entity Room
 * @package    ylab
 * @subpackage meetings
 */
class RoomTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'y_meetings_room';
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap(): array
    {
        return [
            //ID
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ROOM_ENTITY_ID_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки ID - только цифры
                        new Entity\Validator\RegExp('[0-9]+')
                    ];
                },
            ]),
            //Название комнаты
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('ROOM_ENTITY_NAME_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки названия комнаты - только латиница + кириллица и цифры
                        //и начало строки только с букв
                        new Entity\Validator\RegExp('^[а-яА-ЯёЁa-zA-Z0-9]+$'),
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(3, 15),
                    ];
                },
            ]),
            //Активность
            new Entity\BooleanField('ACTIVITY', [
                'required' => true,
                'values' => ['N', 'Y'],
                'title' => Loc::getMessage('ROOM_ENTITY_ACTIVITY_FIELD'),
            ]),
            //ID интеграции
            new Entity\IntegerField('INTEGRATION_ID', [
                'required' => true,
                Loc::getMessage('ROOM_ENTITY_INTEGRATION_ID_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки ID - только цифры
                        new Entity\Validator\RegExp('[0-9]+')
                    ];
                },
            ]),
            //JOIN на интеграцию (отношение "1 интеграция - N комнат")
            (new Reference(
                'INTEGRATION',
                IntegrationTable::class,
                Join::on('this.INTEGRATION_ID', 'ref.ID')
            ))
                ->configureJoinType('inner')
        ];
    }
}
