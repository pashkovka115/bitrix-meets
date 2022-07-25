<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
?>

<?$APPLICATION->IncludeComponent("bitrix:calendar.grid","",Array(
        "CALENDAR_TYPE" => $arResult["CALENDAR_TYPE"],
        "ALLOW_SUPERPOSE" => "Y",
        "ALLOW_RES_MEETING" => "Y"
    )
);?>
<script type="text/javascript">
    BX.ready(function(){
        BX.Ylab.MeetingCalendar.create();
    })
</script>