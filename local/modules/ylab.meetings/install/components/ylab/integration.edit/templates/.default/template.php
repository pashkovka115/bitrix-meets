<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php
if ($arResult['ADD_SUCCESS_INTEGRATION_NAME']) {
    ?>
    <p>
        <?= Loc::getMessage('ADD_SUCCESS_INTEGRATION_PART_1') . ' "' . $arResult['ADD_SUCCESS_INTEGRATION_NAME']
        . '" ' . Loc::getMessage('ADD_SUCCESS_INTEGRATION_PART_2') ?>
    </p>
<?php } ?>
<form action="" method="GET">
    <input type="hidden" name="step" value="addintegrationform">
    <input type="submit" name="" value="<?= Loc::getMessage('INTEGRATION_FORM_SUBMIT_BUTTON') ?>">
</form>