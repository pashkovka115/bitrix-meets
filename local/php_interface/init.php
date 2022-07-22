<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/composer/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/ylab.meetings/lib/events.php';
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/local/log.txt");
RegisterModuleDependences('calendar', 'OnAfterCalendarEntryAdd', 'ylab.meetings', '\\Ylab\\Meetings\\Events', 'OnAfterCalendarEntryAdd');


