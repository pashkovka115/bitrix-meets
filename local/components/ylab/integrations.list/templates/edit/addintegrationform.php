<?php

use \Bitrix\Main\Localization\Loc;

CJSCore::Init(array('ajax'));
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<div id="my-form">
    <label for="NAME"><?= Loc::getMessage('INTEGRATION_FORM_NAME_FIELD') ?> </label>
    <p><input id="input-name" type="string" name="NAME"></p>

    <label for="ACTIVITY"><?= Loc::getMessage('INTEGRATION_FORM_ACTIVITY_FIELD') ?> </label>
    <p><input id="input-activity" type="checkbox" name="ACTIVITY" value="Y" checked></p>

    <label for="INTEGRATION_REF"><?= Loc::getMessage('INTEGRATION_INTEGRATION_REF_ACTIVITY_FIELD') ?> </label>
    <p><input id="input-inegrationref" type="string" name="INTEGRATION_REF"></p>

    <label for="LOGIN"><?= Loc::getMessage('INTEGRATION_FORM_LOGIN_FIELD') ?> </label>
    <p><input id="input-login" type="string" name="LOGIN"></p>

    <label for="PASSWORD"><?= Loc::getMessage('INTEGRATION_FORM_PASSWORD_FIELD') ?> </label>
    <p><input id="input-password" type="password" name="PASSWORD"></p>

</div>
<div id="errors"></div>
<button id="my-button"><?= Loc::getMessage('INTEGRATION_FORM_SUBMIT_BUTTON') ?></button>
<div id="my-result" style="margin:10px 0;padding:.5em;border:1px solid #ececec;"></div>


<script src="/local/components/ylab/integrations.list/templates/edit/script.js"></script>
