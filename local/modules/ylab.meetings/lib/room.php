<?php

namespace Ylab\Meetings;

use Bitrix\Calendar\Internals\TypeTable;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

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
                        new Entity\Validator\RegExp('/[0-9]+/'),
                    ];
                },
            ]),
            //Название комнаты
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('ROOM_ENTITY_NAME_FIELD'),
                'validation' => function () {
                    return [
                        // Уникальность названия
                        new Entity\Validator\Unique(),
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(3, 15),
                        // Регулярное выражение для проверки
                        // ^[a-zA-Z\p{Cyrillic}] - первый символ  с буквы (кириллицы/латиницы)
                        // [a-zA-Z\p{Cyrillic}0-9\s\-] - остальные символы могут быть буквами и цифрами и символ '-'
                        // /s - пробел
                        // /u - для обозначения того что внутри фигурных скобок Cyrillic это набор юникод-символов
                        new Entity\Validator\RegExp('/^[a-zA-Z\p{Cyrillic}][a-zA-Z\p{Cyrillic}0-9\s\-]/u'),
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
                        new Entity\Validator\RegExp('/[0-9]+/')
                    ];
                },
            ]),
            //JOIN на интеграцию (отношение "1 интеграция - N комнат")
            (new Reference(
                'INTEGRATION',
                IntegrationTable::class,
                Join::on('this.INTEGRATION_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),

            //ID типа календаря
            new Entity\IntegerField('CALENDAR_TYPE_XML_ID', [
                'required' => true,
                Loc::getMessage('ROOM_ENTITY_INTEGRATION_ID_FIELD'),
            ]),
            //JOIN на интеграцию (отношение "1:1")
            (new Reference(
                'CALENDARTYPE',
                TypeTable::class,
                Join::on('this.CALENDAR_TYPE_XML_ID', 'ref.XML_ID')
            ))
                ->configureJoinType('inner')
        ];
    }
}
