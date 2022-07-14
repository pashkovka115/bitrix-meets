<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\Localization\Loc;

/**
 * Class for ORM Entity Integration
 * @package    ylab
 * @subpackage meetings
 */
class IntegrationTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'y_meetings_integration';
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
                'title' => Loc::getMessage('INTEGRATION_ENTITY_ID_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки - только цифры
                        new Entity\Validator\RegExp('[0-9]+')
                    ];
                },
            ]),
            //Название
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_NAME_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки латиницы и цифр и пробелов
                        //и начало строки только с букв
                        new Entity\Validator\RegExp('(^[a-zA-Z0-9 ]+$)'),
                    ];
                },
            ]),
            //Активность
            new Entity\BooleanField('ACTIVITY', [
                'required' => true,
                'values' => ['N', 'Y'],
                'title' => Loc::getMessage('INTEGRATION_ENTITY_ACTIVITY_FIELD'),
            ]),
            //Ссылка на класс интеграции
            new Entity\StringField('INTEGRATION_REF', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_INTEGRATION_REF_FIELD'),
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки пути
                        new Entity\Validator\RegExp('(^(.+)(\\\\|\/)([^\/]+)$)')
                    ];
                },
            ]),
            //Логин от интеграции
            new Entity\StringField('LOGIN', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_LOGIN_FIELD'),
                'validation' => function () {
                    return [
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(4, 15),
                    ];
                },
            ]),
            //Пароль от интеграции
            new Entity\StringField('PASSWORD', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_PASSWORD_FIELD'),
                'validation' => function () {
                    return [
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(4, 15),
                    ];
                },
            ]),
            //Обратный референс (для реализации двунаправленности) (отношение "1 интеграция - N комнат")
            (new OneToMany('ROOMS', RoomTable::class, 'INTEGRATION'))
                ->configureJoinType('inner'),
        ];
    }
}
