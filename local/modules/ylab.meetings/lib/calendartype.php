<?php

namespace Ylab\Meetings;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;


class CalendarTypeTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_calendar_type';
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap(): array
    {
        return [
            new Entity\StringField('XML_ID', [
                'primary' => true,
                'required' => true,
                'title' => 'Идентификатор типа календаря',
                'validation' => function () {
                    return [
                        // Уникальность названия
                        new Entity\Validator\Unique(),
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(3, 255),
                        new Entity\Validator\RegExp('/^[a-zA-Z0-9\_]+'),
                    ];
                },
            ]),
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => 'Название',
                'validation' => function () {
                    return [
                        //Проверка на минимальную и максимальную длину строки
                        new Entity\Validator\Length(3, 255),
                        new Entity\Validator\RegExp('/^[a-zA-Z\p{Cyrillic}][a-zA-Z\p{Cyrillic}0-9\s\-]/u'),
                    ];
                },
            ]),
            new Entity\TextField('DESCRIPTION', [
                'title' => 'Описание',
            ]),
            new Entity\StringField('EXTERNAL_ID', [
                'title' => 'Внешний идентификатор',
                'size' => 100
            ]),
            new Entity\BooleanField('ACTIVE', [
                'required' => true,
                'values' => ['N', 'Y'],
                'title' => 'Активный',
            ]),
        ];
    }
}
