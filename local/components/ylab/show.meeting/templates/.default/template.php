<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("ui.bootstrap4");
?>
<select id="select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
    <?foreach($arResult as $arItems):?>  <!-- генерация доступных переговорных -->
        <option value="<?=$arItems["ID"]?>"><?=$arItems["NAME"]?></option>
    <?endforeach;?>
</select>
