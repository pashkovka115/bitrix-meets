<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
use Bitrix\Main\Localization\Loc;
?>

<script type="text/javascript">
    var arElementsObj = new arElements(<?=CUtil::PHPToJSObject($arResult["MEETING_LIST"]);?>);
    BX.message({SELECT_TYPE:'<?=Loc::getMessage("YLAB_MEETING_CALENDAR_SELECT_TYPE")?>'});
</script>

<?$APPLICATION->IncludeComponent("bitrix:calendar.grid","",Array(
        "CALENDAR_TYPE" => $arResult["CALENDAR_TYPE"],
        "OWNER_ID" => $USER->GetID(),
        "ALLOW_SUPERPOSE" => "Y",
        "ALLOW_RES_MEETING" => "Y"
    )
);?>

<script type="text/javascript">
    BX.ready(function(){
        BX.Ylab.MeetingCalendar.create();
    })
</script>