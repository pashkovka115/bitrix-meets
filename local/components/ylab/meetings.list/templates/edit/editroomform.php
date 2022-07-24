<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php
$res = Ylab\Meetings\RoomTable::getList([
  'filter' => ['ID' => $arResult['ID']],
  'select' => [
    "*",
  ]]);

foreach ($res->fetchAll() as $row) {
    $fields = [
      "ID" => $row['ID'],
      "NAME" => $row['NAME'],
      "ACTIVITY" => $row['ACTIVITY'],
      "INTEGRATION_ID" => $row['INTEGRATION_ID'],
    ];
}
AddMessage2Log($fields);
?>
<form action="<?= POST_FORM_ACTION_URI ?>" method="POST">
    <input type="hidden" name="action" value="submitedit">
    <?= bitrix_sessid_post() ?>

    <input type="hidden" name="ID" value=<?= $fields['ID'] ?>>

    <label for="NAME"><?= Loc::getMessage('YLAB_MEETING_LIST_ROOM_NAME') ?> </label>
    <p><input type="string" name="NAME" value="<?= $fields['NAME'] ?>"/></p>

    <label for="ACTIVITY"><?= Loc::getMessage('YLAB_MEETING_LIST_ACTIVITY_NAME') ?> </label>
    <p><input type="checkbox" name="ACTIVITY" id="is_avilable"
              value="Y" <?= $fields['ACTIVITY'] == 'Y' ? 'checked' : '' ?>></p>

    <label for="INTEGRATION_ID"><?= Loc::getMessage('YLAB_MEETING_LIST_INTEGRATION_ID_NAME') ?> </label>
    <p><input type="string" name="INTEGRATION_ID" value="<?= $fields['INTEGRATION_ID'] ?>"></p>

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
    <input type="submit" name="" value="<?= Loc::getMessage('YLAB_MEETING_LIST_EDIT_SUBMIT_BUTTON') ?>">
</form>