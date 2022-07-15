<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();


$classes = [
    Ylab\Meeting\Zoom\Auth::class => 'zoom/auth.php',
    Ylab\Meeting\Zoom\Meeting::class => 'zoom/meeting.php',
    Ylab\Meeting\Zoom\Settings::class => 'zoom/settings.php',
];

spl_autoload_register(function ($class) use ($classes) {
    $class = trim($class, '\\');
    $path = strtr($_SERVER["DOCUMENT_ROOT"] . "/local/modules/ylab.meetings/lib/" . strtolower($classes[$class]),
        '_\\', '//');

    if (file_exists($path)) {
        require_once $path;
    } else {
        echo 'Файл ' . $path . ' не найден';
    }
});