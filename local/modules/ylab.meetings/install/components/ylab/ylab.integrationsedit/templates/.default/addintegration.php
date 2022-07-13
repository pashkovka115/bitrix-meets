<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<form action="" method="POST">
    <input type="hidden" name="step" value="addintegration">

    <label for="NAME"><?= Loc::getMessage('INTEGRATION_FORM_NAME_FIELD') ?> </label>
    <p><input type="string" name="NAME"/></p>

    <label for="ACTIVITY"><?= Loc::getMessage('INTEGRATION_FORM_ACTIVITY_FIELD') ?> </label>
    <p><input type="checkbox" name="ACTIVITY" id="is_avilable" value="Y" checked></p>

    <label for="INTEGRATION_REF"><?= Loc::getMessage('INTEGRATION_INTEGRATION_REF_ACTIVITY_FIELD') ?> </label>
    <p><input type="string" name="INTEGRATION_REF"></p>

    <label for="LOGIN"><?= Loc::getMessage('INTEGRATION_FORM_LOGIN_FIELD') ?> </label>
    <p><input type="string" name="LOGIN"></p>

    <label for="PASSWORD"><?= Loc::getMessage('INTEGRATION_FORM_PASSWORD_FIELD') ?> </label>
    <p><input type="password" name="PASSWORD"></p>
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
    <input type="submit" name="" value="<?= Loc::getMessage('INTEGRATION_FORM_SUBMIT_BUTTON') ?>">
</form>