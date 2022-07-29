<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

CJSCore::Init(array('ajax'));

?>
<script src="/local/components/ylab/integrations.list/templates/grid/popups/popupscript.js"></script>
<div id="add-form">
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
