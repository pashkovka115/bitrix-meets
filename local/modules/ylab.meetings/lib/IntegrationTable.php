<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

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
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки латиницы и цифр
                        //и начало строки только с букв
                        new Entity\Validator\RegExp('^[a-zA-Z0-9]+$'),
                    ];
                },
            ]),
            //Активность
            new Entity\BooleanField('ACTIVITY', [
                'required' => true,
                'values' => ['N', 'Y'],
            ]),
            //Ссылка на класс интеграции
            new Entity\StringField('INTEGRATION_REF', [
                'required' => true,
                'validation' => function () {
                    return [
                        //Регулярное выражение для проверки пути
                        new Entity\Validator\RegExp('^(.+)\/([^\/]+)$')
                    ];
                },
            ]),
            //Логин от интеграции
            new Entity\StringField('LOGIN', [
                'required' => true,
                'validation' => function () {
                    return [
                        /*
                         * Регулярное выражение для проверки логина
                         * символы могут быть буквы и цифры,
                         * первый символ обязательно буква
                        */
                        new Entity\Validator\RegExp('^[a-zA-Z][a-zA-Z0-9-_\.]$'),
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(4, 15),
                    ];
                },
            ]),
            //Пароль от интеграции
            new Entity\StringField('PASSWORD', [
                'required' => true,
                'validation' => function () {
                    return [
                        /*
                         * Регулярное выражение для проверки логина
                         * cтрочные и прописные латинские буквы, цифры, спецсимволы. минимум 8 символов
                        */
                        new Entity\Validator\RegExp('(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z
                        ]).*$'),
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
