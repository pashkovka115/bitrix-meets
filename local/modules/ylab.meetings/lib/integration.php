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
                        new Entity\Validator\RegExp('/[0-9]+/')
                    ];
                },
            ]),
            //Название
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_NAME_FIELD'),
                'validation' => function () {
                    return [
                        // Уникальность названия
                        new Entity\Validator\Unique(),
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(3, 15),
                        //Регулярное выражение для проверки
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
                'title' => Loc::getMessage('INTEGRATION_ENTITY_ACTIVITY_FIELD'),
            ]),
            //Ссылка на класс интеграции
            new Entity\StringField('INTEGRATION_REF', [
                'required' => true,
                'title' => Loc::getMessage('INTEGRATION_ENTITY_INTEGRATION_REF_FIELD'),
                'validation' => function () {
                    return [
                        // Регулярное выражение для проверки пути
                        // Путь должен быть вида Ylab\\Integrations\\Zoom
                        new Entity\Validator\RegExp('/^(.+)\\\\(.+)$/')
                    ];
                },
            ]),
            //Логин от интеграции
            // Логин нужен не для всех сервисов
            new Entity\StringField('LOGIN', [
                'title' => Loc::getMessage('INTEGRATION_ENTITY_LOGIN_FIELD'),
            ]),
            //Пароль от интеграции
            // Пароль нужен не для всех сервисов
            new Entity\StringField('PASSWORD', [
                'title' => Loc::getMessage('INTEGRATION_ENTITY_PASSWORD_FIELD'),
            ]),
            //Обратный референс (для реализации двунаправленности) (отношение "1 интеграция - N комнат")
            (new OneToMany('ROOMS', RoomTable::class, 'INTEGRATION'))
                ->configureJoinType('inner'),
        ];
    }
}
