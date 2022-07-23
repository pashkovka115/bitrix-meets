<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>
  <p>1. <a href="/meet/oauth-zoom-ylab/start" target="_blank">Перейти на страницу авторизации Zoom</a></p>
  <p>2. <a href="/meet/oauth-zoom-ylab/get-link-to-room?get-room=Y">Получить ссылку на комнату Zoom</a></p>
<?php
if (isset($_GET['get-room'])){

    $integration_id = 1;

    if ($integration_id == 0){
        throw new Exception('Необходимо передать ID интеграции в файле: ' . __FILE__);
    }

    $integration = \Ylab\Meetings\Integrations\IntegrationBase::init($integration_id);

    echo '<pre>';
    print_r($integration->getLink());
    echo '</pre>';
}

?>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>