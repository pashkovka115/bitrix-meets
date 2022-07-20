<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

if (!function_exists('setMessage')) {
    function setMessage($message, $type = 'error')
    {
        $_SESSION['errors'][] = [
            'message' => $message,
            'type' => $type
        ];
    }
}

if (!function_exists('getMessages')){
    function getMessages()
    {
        $tmp = [];
        if (isset($_SESSION['errors'])){
            $tmp = $_SESSION['errors'];
        }
        $_SESSION['errors'] = [];

        return $tmp;
    }
}

if (!function_exists('hasMessages')){
    function hasMessages()
    {
        if (isset($_SESSION['errors'])){
            return (bool)count($_SESSION['errors']);
        }
        return false;
    }
}