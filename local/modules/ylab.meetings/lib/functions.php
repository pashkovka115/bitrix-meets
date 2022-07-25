<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

if (!function_exists('setMessage')) {
    function setMessage($message, $type = 'error')
    {
        $_SESSION['ylab_errors'][] = [
            'message' => $message,
            'type' => $type
        ];
    }
}

if (!function_exists('getMessages')){
    function getMessages()
    {
        $tmp = [];
        if (isset($_SESSION['ylab_errors'])){
            $tmp = $_SESSION['ylab_errors'];
        }
        $_SESSION['ylab_errors'] = [];

        return $tmp;
    }
}

if (!function_exists('hasMessages')){
    function hasMessages()
    {
        if (isset($_SESSION['ylab_errors'])){
            return (bool)count($_SESSION['ylab_errors']);
        }
        return false;
    }
}