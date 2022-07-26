<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
use Bitrix\Main\Localization\Loc;
?>

<?$APPLICATION->IncludeComponent("bitrix:calendar.grid","",Array(
        "CALENDAR_TYPE" => $arResult["CALENDAR_TYPE"],
        "OWNER_ID" => $USER->GetID(),
        "ALLOW_SUPERPOSE" => "Y",
        "ALLOW_RES_MEETING" => "Y"
    )
);?>
<div id="select-meet">
    <form action="" method="get">
        <select name="calendar_type" onchange="javascript:this.form.submit()">
            <option><?= Loc::getMessage('YLAB_MEETING_CALENDAR_SELECT_TYPE') ?></option>
            <?foreach($arResult["MEETING_LIST"] as $arItems):?>
                <option value="<?=$arItems["CALENDAR_TYPE_XML_ID"]?>"><?=$arItems["CALENDAR_TYPE_XML_ID"]?></option>
            <?endforeach;?>
        </select>
    </form>
</div>