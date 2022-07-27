<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<form action="" method="POST">
    <input type="hidden" name="action" value="submitedit">
    <?= bitrix_sessid_post() ?>

    <input type="hidden" name="ID" value=<?= $arResult['ID'] ?>>

    <label for="NAME"><?= Loc::getMessage('INTEGRATION_FORM_NAME_FIELD') ?> </label>
    <p><input type="string" name="NAME" value="<?= $arResult['NAME'] ?>"/></p>

    <label for="ACTIVITY"><?= Loc::getMessage('INTEGRATION_FORM_ACTIVITY_FIELD') ?> </label>
    <p><input type="checkbox" name="ACTIVITY" id="is_avilable"
              value="Y" <?= $arResult['ACTIVITY'] == 'Y' ? 'checked' : '' ?>></p>

    <label for="INTEGRATION_REF"><?= Loc::getMessage('INTEGRATION_INTEGRATION_REF_ACTIVITY_FIELD') ?> </label>
    <p><input type="string" name="INTEGRATION_REF" value="<?= $arResult['INTEGRATION_REF'] ?>"></p>

    <label for="LOGIN"><?= Loc::getMessage('INTEGRATION_FORM_LOGIN_FIELD') ?> </label>
    <p><input type="string" name="LOGIN" value="<?= $arResult['LOGIN'] ?>"></p>

    <label for="PASSWORD"><?= Loc::getMessage('INTEGRATION_FORM_PASSWORD_FIELD') ?> </label>
    <p><input type="password" name="PASSWORD" value="<?= $arResult['PASSWORD'] ?>"></p>
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



////////////

<div id="add-integrations">
    <input type="hidden" name="action" value="add">
    <?= bitrix_sessid_post() ?>
    <label for="NAME"><?= Loc::getMessage('INTEGRATION_FORM_NAME_FIELD') ?> </label>
    <p><input type="string" id="input-name" name="NAME"/></p>

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
    <button id="my-1button"><?= Loc::getMessage('INTEGRATION_FORM_SUBMIT_BUTTON') ?>
</div>