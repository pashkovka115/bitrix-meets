<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<form action="<?= POST_FORM_ACTION_URI ?>" method="POST">
    <input type="hidden" name="action" value="submitadd">
    <?= bitrix_sessid_post() ?>
    <label for="NAME"><?= Loc::getMessage('YLAB_MEETING_LIST_ROOM_NAME') ?> </label>
    <p><input type="string" name="NAME"/></p>

    <label for="ACTIVITY"><?= Loc::getMessage('YLAB_MEETING_LIST_ACTIVITY_NAME') ?> </label>
    <p><input type="checkbox" name="ACTIVITY" id="is_avilable" value="Y" checked></p>

    <label for="INTEGRATION_ID"><?= Loc::getMessage('YLAB_MEETING_LIST_INTEGRATION_ID_NAME') ?> </label>
    <p><input type="string" name="INTEGRATION_ID"></p>

    <?php
    if ($arResult['SUBMIT_ERROR']) {
        foreach ($arResult['SUBMIT_ERROR'] as $errorMessage) {
            ?>
            <p><span style="color: red; ">
                    <?= $errorMessage ?>
                </span>
            </p>
        <?php }
    } ?>
    <input type="submit" name="" value="<?= Loc::getMessage('YLAB_MEETING_LIST_ADD_SUBMIT_BUTTON') ?>">
</form>