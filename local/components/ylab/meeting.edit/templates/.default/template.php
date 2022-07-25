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

if (hasMessages()) {
    ?>
  <ul class="ylab-messages"><?php
    foreach (getMessages() as $message) {
        ?>
      <li class="<?= $message['type'] ?>"><?= $message['message'] ?></li><?php
    }
    ?></ul><?php
}
?>
<form method="post" action="<?= POST_FORM_ACTION_URI ?>">
    <?= bitrix_sessid_post() ?>
  <input type="hidden" name="lang" value="<?= LANG ?>">

  <label><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_CALENDAR_TYPE_XML_ID') ?><br>
    <input type="text" name="CALENDAR_TYPE_XML_ID" value="<?= $arResult['ITEM']['CALENDAR_TYPE_XML_ID'] ?? '' ?>">
  </label><br><br>
  <label><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_CALENDAR_TYPE_NAME') ?><br>
    <input type="text" name="CALENDAR_TYPE_NAME" value="<?= $arResult['ITEM']['CALENDAR_TYPE_NAME'] ?? '' ?>">
  </label><br><br>
  <label><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_CALENDAR_TYPE_DESCRIPTION') ?><br>
    <textarea name="CALENDAR_TYPE_DESCRIPTION"><?= $arResult['ITEM']['CALENDAR_TYPE_DESCRIPTION'] ?? '' ?></textarea>
  </label><br><br>

  <label for="integr"><?= Loc::getMessage('YLAB_MEETINGS_MODULE_INTEGRATION') ?></label><br>
  <select name="INTEGRATION_ID" id="integr">
      <?php
      foreach ($arResult['INTEGRATIONS'] as $integration) {
          if ($integration['ID'] == $arResult['ITEM']['INTEGRATION_ID']){
              $selected = ' selected';
          }else{
            $selected = '';
          }
          ?>
        <option value="<?= $integration['ID'] ?>"<?= $selected ?>><?= $integration['NAME'] ?></option>
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
    <label for="name"><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_NAME') ?></label><br>
    <input type="text" name="NAME" value="<?= $arResult['ITEM']['NAME'] ?? '' ?>" id="name">
  </p>
  <p>
    <label for="active"><?= Loc::getMessage('YLAB_MEETINGS_MODULE_FORM_ACTIVE') ?></label>
    <input type="checkbox" name="ACTIVITY" <?php
    if ($arResult['ITEM']['ACTIVITY'] == 'Y') {
        echo 'checked';
    } ?> id="active">
  </p>
  <p>
    <input type="submit" name="meeting" value="Отправить">
    <?php if (isset($arResult['ITEM']['ID'])){ ?>
    &nbsp;&nbsp;<input type="submit" name="delete_room" value="Удалить">
    <?php } ?>
  </p>
</form>


