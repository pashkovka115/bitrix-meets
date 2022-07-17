<?php use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

?>
<div class="list">

    <h3><?= Loc::getMessage('YLAB.MEETING.LIST.TABLE.PREFIX') ?> <?= $arResult['TABLE_NAME'] ?></h3>
    <p></p>

    <table border="1" width="100%" cellpadding="5">

        <tr>
            <?php foreach ($arResult['GRID_HEAD'] as $arItem) { ?>
                <th><?= $arItem['name'] ?></th>
            <?php } ?>
        </tr>

        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <tr>
                <?php foreach ($arItem as $key => $value) { ?>
                    <?= '<td>' ?><?= $value ?><?= '</td>' ?>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>

</div>