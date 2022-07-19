<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Bitrix\Main\Loader::includeModule('ylab.meetings');

$settings = new \Ylab\Meetings\Zoom\Settings();

$url = "https://zoom.us/oauth/authorize?response_type=code&client_id=".$settings->getClientId()."&redirect_uri=".$settings->getRedirectURI();
?>

<a href="<?= $url; ?>">Войти с помощью Zoom</a>

