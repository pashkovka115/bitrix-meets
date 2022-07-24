<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;


$arComponentParameters = [
    'GROUPS' => [
        'VARIABLES' => [
            'NAME' => Loc::getMessage('YLAB_MEETING_ADD_OUTPUT_VARS'),
            "SORT" => 100
        ],
    ],
    'PARAMETERS' => [
        'ELEMENT_ID' => [
            'PARENT' => 'VARIABLES',
            'NAME' => Loc::getMessage('YLAB_MEETING_ADD_ID_MEETING'),
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["ELEMENT_ID"]}',
            'VARIABLES' => ['ELEMENT_ID']
        ],
        'CACHE_TIME' => ['DEFAULT' => 86400],
    ]
];

