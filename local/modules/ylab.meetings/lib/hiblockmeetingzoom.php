<?php

namespace Ylab\Meetings;

use Bitrix\Main\Loader;
use CUserTypeEntity;
use Bitrix\Highloadblock as HL;

class HiBlockMeetingZoom
{
    const HILOADBLOCK_NAME = 'YlabMeetingsZoom';
    const TABLE_NAME = 'ylab_meeting';

    public function __construct()
    {
        Loader::IncludeModule('highloadblock');
    }


    public function create()
    {
        $result = HL\HighloadBlockTable::add([
            'NAME' => self::HILOADBLOCK_NAME,
            'TABLE_NAME' => self::TABLE_NAME,
        ]);

        if ($result->isSuccess()) {
            $id = $result->getId();
            $UF_id = 'HLBLOCK_' . $id;

            $langs = [
                'ru' => 'Переговорные Zoom',
                'en' => 'Meetings Zoom'
            ];

            foreach ($langs as $key => $val) {
                HL\HighloadBlockTable::add([
                    'ID' => $id,
                    'LID' => $key,
                    'NAME' => $val
                ]);
            }

            $fields = [
                'UF_ID_EVENT_CALENDAR' => [
                    'ENTITY_ID' => $UF_id,
                    'FIELD_NAME' => 'UF_ID_EVENT_CALENDAR',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    "EDIT_FORM_LABEL" => ['ru' => 'id события календаря', 'en' => 'id event calendar'],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_ID_ROOM' => [
                    'ENTITY_ID' => $UF_id,
                    'FIELD_NAME' => 'UF_ID_ROOM',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    "EDIT_FORM_LABEL" => ['ru' => 'id конференции', 'en' => 'id conference'],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_CALENDAR_TYPE_XML_ID' => [
                    'ENTITY_ID' => $UF_id,
                    'FIELD_NAME' => 'UF_CALENDAR_TYPE_XML_ID',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    "EDIT_FORM_LABEL" => ['ru' => 'тип календаря', 'en' => 'type calendar'],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_URL_START' => [
                    'ENTITY_ID' => $UF_id,
                    'FIELD_NAME' => 'UF_URL_START',
                    'USER_TYPE_ID' => 'url',
                    'MANDATORY' => 'Y',
                    "EDIT_FORM_LABEL" => ['ru' => 'ссылка на переговорную', 'en' => 'link to meeting'],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
            ];

            foreach ($fields as $field) {
                $userField = new CUserTypeEntity;
                $userField->Add($field);
            }
        }
    }


    public function delete()
    {
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => self::HILOADBLOCK_NAME]
        ])->fetch();

        if ($hlblock) {
            HL\HighloadBlockTable::delete($hlblock['ID']);
        }
    }
}