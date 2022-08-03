<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php
Bitrix\Main\UI\Extension::load("ui.dialogs.messagebox");


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

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
        <?= $arResult['BUTTONS']['ADD'] ?>
        <div>
            <?php
            $APPLICATION->IncludeComponent(
                'bitrix:main.ui.grid',
                '',
                [
                    'GRID_ID' => $arResult['GRID_ID'],
                    'COLUMNS' => $arResult['GRID_COLUMNS'],
                    'ROWS' => $arResult['GRID_ROWS'],
                    'SHOW_ROW_CHECKBOXES' => false,
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
                    'AJAX_OPTION_JUMP' => 'Y',
                    "AJAX_OPTION_STYLE" => "Y",
                    'SHOW_CHECK_ALL_CHECKBOXES' => false,
                    'SHOW_ROW_ACTIONS_MENU' => true,
                    'SHOW_GRID_SETTINGS_MENU' => true,
                    'SHOW_NAVIGATION_PANEL' => true,
                    'SHOW_PAGINATION' => true,
                    'SHOW_SELECTED_COUNTER' => false,
                    'SHOW_TOTAL_COUNTER' => true,
                    'SHOW_PAGESIZE' => true,
                    'SHOW_ACTION_PANEL' => false,
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
            Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/css/main/grid/webform-button.css');
            ?>

        </div>
    </div>
</div>
