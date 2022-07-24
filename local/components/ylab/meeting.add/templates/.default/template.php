<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var \CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var array $templateData */
/** @var \CBitrixComponent $component */
$this->setFrameMode(true);

?>

<form method="post" action="<?= POST_FORM_ACTION_URI ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="hidden" name="action" value="submitadd">
    <?= bitrix_sessid_post() ?>
    <label for="NAME"><?= Loc::getMessage('YLAB_MEETING_ADD_COMPONENT_NAME') ?> </label>
    <p><input type="string" name="NAME"/></p>

    <label for="ACTIVITY"><?= Loc::getMessage('YLAB_MEETING_ADD_COMPONENT_ACTIVE') ?> </label>
    <p><input type="checkbox" name="ACTIVITY" id="is_avilable" value="Y" checked></p>

    <label for="INTEGRATION_ID"><?= Loc::getMessage('YLAB_MEETING_ADD_COMPONENT_INTEGRATION') ?> </label>
    <!--    <p><input type="string" name="INTEGRATION_ID"></p>-->
    <select name="INTEGRATION_ID" id="integr">
        <?php
        foreach ($arResult['INTEGRATIONS'] as $integration) {
            ?>
            <option value="<?= $integration['ID'] ?>"><?= $integration['NAME'] ?></option>
            <?php
        }
        ?>
    </select>

    <?php
    if ($arResult['SUBMIT_ERROR']) {
        foreach ($arResult['SUBMIT_ERROR'] as $errorMessage) {
            ?>
            <p><span style="color: #ff0000; ">
                    <?= "Error: ".$errorMessage ?>
                </span>
            </p>
        <?php }
    } ?>
    <input type="submit" name="" value="<?= Loc::getMessage('YLAB_MEETING_ADD_COMPONENT_SUBMIT_BUTTON') ?>">
</form>






