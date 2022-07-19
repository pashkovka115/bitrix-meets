<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$moduleId = 'ylab.meetings';

Bitrix\Main\Loader::includeModule($moduleId);


$url = "https://zoom.us/oauth/authorize?response_type=code&client_id="
    . COption::GetOptionString($moduleId, 'client_id')
    . "&redirect_uri="
    . COption::GetOptionString($moduleId, 'zoom_redirect_url');
?>

<a href="<?= $url; ?>">Войти с помощью Zoom</a>

