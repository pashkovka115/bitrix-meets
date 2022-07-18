<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->SetTitle("Список интеграций");

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;

Loader::includeModule('ylab.meetings');

$list_id = 'integration_list';
$grid_options = new GridOptions($list_id);
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new PageNavigation('request_list');
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();

$filterOption = new Bitrix\Main\UI\Filter\Options($list_id);
$filterData = $filterOption->getFilter([]);
$filter = [];

foreach ($filterData as $k => $v) {
    // Тут разбор массива $filterData из формата, в котором его формирует main.ui.filter в формат, который подойдет для вашей выборки.
    // Обратите внимание на поле "FIND", скорее всего его вы и захотите засунуть в фильтр по NAME и еще паре полей
    $filter['NAME'] = "%" . $filterData['FIND'] . "%";
}

$res = Ylab\Meetings\IntegrationTable::getList([
    'filter' => $filter,
    'select' => [
        "*",
    ],
    'offset' => $nav->getOffset(),
    'limit' => $nav->getLimit(),
    'order' => $sort['sort']
]);

$ui_filter = [
    ['id' => 'NAME', 'name' => 'Название', 'type' => 'text', 'default' => true],
    ['id' => 'ACTIVITY', 'name' => 'Активность'],
    ['id' => 'INTEGRATION_REF', 'name' => 'Ссылка на класс интеграции'],
    ['id' => 'LOGIN', 'name' => 'Логин'],
    ['id' => 'PASSWORD', 'name' => 'Пароль']
];
?>
<div>
    <?php $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
        'FILTER_ID' => $list_id,
        'GRID_ID' => $list_id,
        'FILTER' => $ui_filter,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true
    ]); ?>

    <div style="clear: both;"></div>
    <div class="">
        <form action="" method="POST">
            <input type="hidden" name="step" value="addintegrationform">
            <?php
            /* Добавление и рендер кнопки "ADD_INTEGRATION"*/
            \Bitrix\Main\UI\Extension::load("ui.buttons.icons");
            $addButton = new Bitrix\UI\Buttons\CreateButton();
            $addButton->addAttribute('type = "submit"');
            $addButton->addClass('ui-btn-icon-add');
            $addButton->setText(Loc::getMessage('ADD_INTEGRATION'));
            $addButton->setStyles(['float' => 'right']);
            echo $addButton->render();
            ?>
        </form>

        <div class="">
            <?php
            $columns = [];
            $columns[] = ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true, 'editable' => false];
            $columns[] = ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true,
                'editable' => true];
            $columns[] = ['id' => 'ACTIVITY', 'name' => 'Активность', 'sort' => 'ACTIVITY', 'default' => true,
                'editable' => true];
            $columns[] = ['id' => 'INTEGRATION_REF', 'name' => 'Ссылка', 'sort' => 'INTEGRATION_REF', 'default' => true,
                'editable' => true];
            $columns[] = ['id' => 'LOGIN', 'name' => 'Логин', 'sort' => 'LOGIN', 'default' => true, 'editable' => true];
            $columns[] = ['id' => 'PASSWORD', 'name' => 'Пароль', 'sort' => 'PASSWORD', 'default' => true,
                'editable' => true];
            foreach ($res->fetchAll() as $row) {
                $list[] = [
                    'data' => [
                        "ID" => $row['ID'],
                        "NAME" => $row['NAME'],
                        "ACTIVITY" => $row['ACTIVITY'],
                        "INTEGRATION_REF" => $row['INTEGRATION_REF'],
                        "LOGIN" => $row['LOGIN'],
                        "PASSWORD" => $row['PASSWORD'],
                    ],
                    'actions' => [
                        [
                            'text' => 'Редактировать',
                            'default' => true,
                            'onclick' => 'document.location.href="?op=edit&id=' . $row['ID'] . '"'
                        ], [
                            'text' => 'Удалить',
                            'default' => true,
                            'onclick' => 'if(confirm("Точно?")){document.location.href="?op=delete&id=' . $row['ID'] . '"}'
                        ]
                    ]
                ];
            }
            $snippets = new \Bitrix\Main\Grid\Panel\Snippet();
            $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
                'GRID_ID' => $list_id,
                'COLUMNS' => $columns,
                'ROWS' => $list,
                'SHOW_ROW_CHECKBOXES' => true,
                'NAV_OBJECT' => $nav,
                'AJAX_MODE' => 'Y',
                'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                'PAGE_SIZES' => [
                    ['NAME' => '20', 'VALUE' => '20'],
                    ['NAME' => '50', 'VALUE' => '50'],
                    ['NAME' => '100', 'VALUE' => '100']
                ],
                'AJAX_OPTION_JUMP' => 'N',
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
                                $snippets->getRemoveButton(),
                                $snippets->getEditButton(),
                            ],
                        ]
                    ],
                ],
                'ALLOW_COLUMNS_SORT' => true,
                'ALLOW_COLUMNS_RESIZE' => true,
                'ALLOW_HORIZONTAL_SCROLL' => true,
                'ALLOW_SORT' => true,
                'ALLOW_PIN_HEADER' => true,
                'AJAX_OPTION_HISTORY' => 'N'
            ]);
            ?>
        </div>
    </div>
</div>
