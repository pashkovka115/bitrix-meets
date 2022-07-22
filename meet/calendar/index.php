<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("calendar");


if ($APPLICATION->IncludeComponent(
    "ylab:meeting.calendar",
    ".default",
    array(

    ),
    false
)){
    $APPLICATION->IncludeComponent("bitrix:calendar.grid","",Array(
        "CALENDAR_TYPE" => "user",
        "ALLOW_SUPERPOSE" => "Y",
        "ALLOW_RES_MEETING" => "Y"
    )
);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
