<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php

use Bitrix\Main\Localization\Loc;

global $APPLICATION;
?>

<div class="">
    <div>
        <?php $APPLICATION->includeComponent(
            'bitrix:main.ui.filter',
            '',
            [
                'FILTER_ID' => $arResult['GRID_ID'],
                'GRID_ID' => $arResult['GRID_ID'],
                'FILTER' => $arResult['GRID_FILTER'],
                'VALUE_REQUIRED_MODE' => true,
                'ENABLE_LIVE_SEARCH' => true,
                'ENABLE_LABEL' => true
            ],

        );

        ?>
    </div>
    <div style="clear: both;"></div>
    <?= $arResult['ADD_SUCCESS_INTEGRATION_NAME'] ? Loc::getMessage('YLAB_INTEGRATIONS_LIST_ADD_SUCCESS_PT_1') .
        $arResult['ADD_SUCCESS_INTEGRATION_NAME'] . Loc::getMessage('YLAB_INTEGRATIONS_LIST_ADD_SUCCESS_PT_2')
        : null ?>
    <div class="">
        <form action="<?= $arResult['AJAX_PATH'] ?>" method="POST">
            <input type="hidden" id="addButton" name="action" value="add">
            <?= bitrix_sessid_post() ?>
            <?= $arResult['BUTTONS']['ADD'] ?>
        </form>
        <div>
            <?php
            $APPLICATION->IncludeComponent(
                'bitrix:main.ui.grid',
                '',
                [
                    'GRID_ID' => $arResult['GRID_ID'],
                    'COLUMNS' => $arResult['GRID_COLUMNS'],
                    'ROWS' => $arResult['GRID_ROWS'],
                    'SHOW_ROW_CHECKBOXES' => true,
                    'NAV_OBJECT' => $arResult['GRID_NAV'],
                    'AJAX_MODE' => 'Y',
                    'AJAX_ID' => CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                    'PAGE_SIZES' => [
                        ['NAME' => "5", 'VALUE' => '5'],
                        ['NAME' => '10', 'VALUE' => '10'],
                        ['NAME' => '20', 'VALUE' => '20'],
                        ['NAME' => '50', 'VALUE' => '50'],
                        ['NAME' => '100', 'VALUE' => '100']
                    ],
                    'AJAX_OPTION_JUMP' => 'N',
                    "AJAX_OPTION_STYLE" => "Y",
                    'SHOW_CHECK_ALL_CHECKBOXES' => true,
                    'SHOW_ROW_ACTIONS_MENU' => true,
                    'SHOW_GRID_SETTINGS_MENU' => true,
                    'SHOW_NAVIGATION_PANEL' => true,
                    'SHOW_PAGINATION' => true,
                    'SHOW_SELECTED_COUNTER' => true,
                    'SHOW_TOTAL_COUNTER' => true,
                    'SHOW_PAGESIZE' => true,
                    'SHOW_ACTION_PANEL' => true,
                    'ACTION_PANEL' => [
                        'GROUPS' => [
                            'TYPE' => [
                                'ITEMS' => [
                                    $arResult['BUTTONS']['ACTION']['EDIT'],
                                    $arResult['BUTTONS']['ACTION']['REMOVE'],
                                ],
                            ]
                        ],
                    ],
                    'ALLOW_COLUMNS_SORT' => true,
                    'ALLOW_COLUMNS_RESIZE' => true,
                    'ALLOW_HORIZONTAL_SCROLL' => true,
                    'ALLOW_SORT' => true,
                    'ALLOW_PIN_HEADER' => true,
                    'AJAX_OPTION_HISTORY' => 'N',
                    "AJAX_OPTION_ADDITIONAL" => $arResult['GRID_ID'],
                    'TOTAL_ROWS_COUNT' => $arResult['RECORD_COUNT'],
                ],

            );
            $this->addExternalJs('/local/components/ylab/integrations.list/templates/grid/script.js');
            ?>

        </div>
    </div>

</div>