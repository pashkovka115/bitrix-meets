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
  <label for="integr"><?= Loc::getMessage('YLAB_MEETINGS_MODULE_INTEGRATION') ?></label>
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
  <p><input type="submit" name="meeting" value="Отправить"></p>
</form>


