<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = [
    "NAME" => Loc::getMessage('YLAB.MEETING.EDIT.EDIT.MEETING'),
    "DESCRIPTION" => Loc::getMessage('YLAB.MEETING.EDIT.COMPONENT.FROM'),
    "COMPLEX" => "N",
    "PATH" => [
        "ID" => 'ylab_local',
        "NAME" => 'Ylab',
    ],
];
