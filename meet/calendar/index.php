<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("calendar");


($APPLICATION->IncludeComponent(
    "ylab:meeting.calendar",
    ".default",
    array(
    ),
    false
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
