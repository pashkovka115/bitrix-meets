<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
use Bitrix\Main\Localization\Loc;
?>
<?foreach($arResult as $arItems):?>  //Вывод свойств комнаты
    <p><?= Loc::getMessage('YLAB_METING_DETAIL_NAME') ?> : <?=$arItems["NAME"]?></p>
    <p><?= Loc::getMessage('YLAB_METING_DETAIL_ACTIVITY') ?> : <?if ($arItems["ACTIVITY"] == "y"): ?>
    <?= Loc::getMessage('YLAB_METING_DETAIL_ACTIVITY_Y') ?><? else: ?>
    <?= Loc::getMessage('YLAB_METING_DETAIL_ACTIVITY_N') ?><? endif; ?></p>
    <p><?= Loc::getMessage('YLAB_METING_DETAIL_DATA') ?> : <?=$arItems["MEET_DATE"]?></p>
<?endforeach;?>


