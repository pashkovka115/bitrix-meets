<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<form method="post" action="<?= POST_FORM_ACTION_URI ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="hidden" name="action" value="submitedit">
    <label for="integr"><?= Loc::getMessage('YLAB_MEETING_LIST_INTEGRATION_NAME') ?></label>
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
    if (isset($arResult['ITEM']['ID'])) { ?>
        <input type="hidden" name="ID" value=<?= $arResult['ITEM']['ID'] ?>>
        <?php
    } ?>
    <p>
        <label for="name"><?= Loc::getMessage('YLAB_MEETING_LIST_ROOM_NAME') ?></label><br>
        <input type="text" name="NAME" value="<?= $arResult['ITEM']['NAME'] ?? '' ?>" id="name">
    </p>

    <p>
        <label for="is_avilable"><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_ACTIVE') ?></label>
        <input type="checkbox" name="ACTIVITY" id="is_avilable" value="Y" checked>
    </p>

    <p><input type="submit" name="meeting" value="<?= Loc::getMessage('YLAB_MEETING_LIST_EDIT_SUBMIT_BUTTON') ?>"></p>
</form>