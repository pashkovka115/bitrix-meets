<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Ylab\Meetings\IntegrationTable;

/**
 * Class Component for edit and add integrations
 *
 * @package ylab
 * @subpackage meetings
 */
class IntegrationsListComponent extends CBitrixComponent
{

    /** @var string/null $templateName Имя шаблона компонента */
    private $templateName;
    /** @var string $listId Имя отображаемого списка */
    private string $listId;
    /** @var string $ormClassName Имя сущности ORM */
    private string $ormClassName;
    /** @var array $columnFields Набор полей колонок грида */
    private array $columnFields;
    /** @var array $filterFields Набор полей доступных для фильтрации */
    private array $filterFields;
    /** @var ?PageNavigation $gridNav Параметры навигации грида */
    private ?PageNavigation $gridNav = null;

    /**
     * Метод в свойства класса параметры из вызова компонента
     *
     * @param $arParams
     */
    public function onPrepareComponentParams($arParams): array
    {
        $this->templateName = $this->GetTemplateName();
        $this->arResult['IS_MODULE_LOAD'] = true;
        $this->listId = $arParams['LIST_ID'];
        $this->ormClassName = 'Ylab\Meetings\\' . $arParams['ORM_NAME'];
        $this->columnFields = $arParams['COLUMN_FIELDS'];
        $this->filterFields = $arParams['FILTER_FIELDS'];

        return $arParams;
    }


    /**
     * Метод вызывающий шаблон
     *
     * @return mixed|void|null
     * @throws \Bitrix\Main\LoaderException
     */
    public function executeComponent()
    {
        if (Loader::IncludeModule('ylab.meetings')) {

            $this->showByGrid();
            $this->includeComponentTemplate();

        }
    }


    /**
     * Отображение через грид
     * Получение в $arResult параметров для грида и кнопок
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public function showByGrid(): void
    {

        $this->arResult['GRID_ID'] = $this->getGridId();
        $this->arResult['GRID_COLUMNS'] = $this->getGridColumns();
        $this->arResult['GRID_ROWS'] = $this->getGridRows();
        $this->arResult['GRID_NAV'] = $this->getGridNav();
        $this->arResult['GRID_FILTER'] = $this->getGridFilterParams();
        $this->arResult['RECORD_COUNT'] = $this->getGridNav()->getRecordCount();

        $this->arResult['BUTTONS']['ADD'] = $this->getAddButton();

    }


    /**
     * Возвращает содержимое (тело) таблицы.
     *
     * @return array
     */
    private function getGridRows(): array
    {
        $arRows = [];

        $arItems = $this->getElements();

        foreach ($arItems as $arItem) {
            $arGridElement = [];

            foreach ($this->columnFields as $k => $v) {
                if (is_numeric($k)) {
                    $arGridElement['data'][$v] = $arItem[$v];
                } else {
                    $arGridElement['data'][$k] = $arItem[$k];
                }
            }

            $arGridElement['actions'] = [
                [
                    'text' => Loc::getMessage('YLAB_MEETING_LIST_CLASS_DELETE'),
                    'onclick' => 'BX.Ylab.Integrations.LeftPanelAction(' .
                        CUtil::PhpToJSObject([
                            'action' => 'delete_burger',
                            'id' => $arItem['ID'],
                        ]) . ')'
                ],
                [
                    'text' => Loc::getMessage('YLAB_MEETING_LIST_CLASS_EDIT'),
                    'onclick' => 'BX.Ylab.Integrations.LeftPanelAction(' .
                        CUtil::PhpToJSObject([
                            'action' => 'edit_burger',
                            'id' => $arItem['ID'],
                        ]) . ')'
                ],
            ];
            $arRows[] = $arGridElement;
        }

        return $arRows;
    }

    /**
     * Получение элементов через ORM для шаблона grid
     *
     * @return array
     */
    public function getElements(): array
    {
        $result = [];

        $ormNam = $this->ormClassName;

        $arCurSort = $this->getObGridParams()->getSorting(['sort' => ['ID' => 'DESC']])['sort'];
        $arFilter = $this->getGridFilterValues();

        $elements = $ormNam::GetList([
            'filter' => $arFilter,
            "count_total" => true,
            "offset" => $this->getGridNav()->getOffset(),
            "limit" => $this->getGridNav()->getLimit(),
            'order' => $arCurSort,
            'select' => $this->columnFields,
        ]);

        $this->getGridNav()->setRecordCount($elements->getCount());

        $result = $elements->fetchAll();

        return $result;
    }


    /**
     * Возвращает идентификатор грида.
     *
     * @return string
     */
    private function getGridId(): string
    {
        return 'ylab_meetings_' . $this->listId;
    }


    /**
     * Возращает заголовки таблицы.
     *
     * @return array
     */
    private function getGridColumns(): array
    {

        // Читаем ORM
        $ormFields = $this->ormClassName::getMap();

        // Массив заголовков для грида
        $gridColumns = [];

        foreach ($ormFields as $field) {

            $arr = [];

            // Выбираем типы полей из ORM есть они в настройках вызова
            // (референсное поле не заносится в настройки при вызове)
            if (in_array($field->getName(), $this->columnFields)) {

                $arr['id'] = $field->getName();
                $arr['name'] = $field->getTitle();
                $arr['type'] = $field->getName() == 'ID' ? 'number' : ($field->getName() == 'ACTIVITY' ? 'bool' : 'string');
                $arr['default'] = true;
                $arr['sort'] = $field->getName();

                $gridColumns[] = $arr;
            } // Если существующего поля ORM нет в настройках - обрываем итерацию
            else {
                continue;
            }
        }
        return $gridColumns;
    }

    /**
     * Возвращает настройки отображения грид фильтра.
     *
     * @return array
     */
    private function getGridFilterParams(): array
    {

        // Массив параметров фильтра
        $getGridFilterParams = [];
        foreach ($this->getGridColumns() as $gridHeadElement) {

            $arr = [];
            // Выбираем типы фильтра из ORM есть они в настройках вызова фильтра
            if (in_array($gridHeadElement['id'], $this->filterFields)) {
                $arr['id'] = $gridHeadElement['id'];
                $arr['name'] = $gridHeadElement['name'];
                $arr['type'] = $gridHeadElement['type'];
                $arr['default'] = true;
                $getGridFilterParams[] = $arr;
            } else {
                continue;
            }
        }

        return $getGridFilterParams;
    }


    /**
     * Возвращает единственный экземпляр настроек грида.
     *
     * @return GridOptions
     */
    private function getObGridParams(): GridOptions
    {
        return $this->gridOption ?? $this->gridOption = new GridOptions($this->getGridId());
    }


    /**
     * Параметры навигации грида
     *
     * @return PageNavigation
     */
    private function getGridNav(): PageNavigation
    {
        if ($this->gridNav === null) {
            $this->gridNav = new PageNavigation($this->getGridId());

            $gridOptions = $this->getObGridParams();
            $navParams = $gridOptions->GetNavParams();

            $this->gridNav
                ->allowAllRecords(true)
                ->setPageSize($navParams['nPageSize'])
                ->initFromUri();
        }

        return $this->gridNav;
    }


    /**
     * Возвращает значения грид фильтра.
     *
     * @return array
     */
    public function getGridFilterValues(): array
    {

        $obFilterOption = new FilterOptions($this->getGridId());
        $arFilterData = $obFilterOption->getFilter();
        $baseFilter = array_intersect_key($arFilterData, array_flip($obFilterOption->getUsedFields()));
        $formatedFilter = $this->prepareFilter($arFilterData, $baseFilter);

        return array_merge(
            $baseFilter,
            $formatedFilter
        );
    }

    /**
     * Подготавливает параметры фильтра
     *
     * @param array $arFilterData
     * @param array $baseFilter
     * @return array
     */
    public function prepareFilter(array $arFilterData, &$baseFilter = []): array
    {
        $arFilter = [];


        foreach ($this->getGridFilterParams() as $gridFilterParam) {

            if ($gridFilterParam['type'] == 'number') {

                if (!empty($arFilterData[$gridFilterParam['id'] . '_from'])) {
                    $arFilter[' >= ' . $gridFilterParam['id']] = (int)$arFilterData[$gridFilterParam['id'] . '_from'];
                }
                if (!empty($arFilterData[$gridFilterParam['id'] . '_to'])) {
                    $arFilter[' <= ' . $gridFilterParam['id']] = (int)$arFilterData[$gridFilterParam['id'] . '_to'];
                }
            }
        }

        return $arFilter;
    }


    /**
     * Метод возвращающий html код кнопки добавления
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public function getAddButton(): string
    {
        \Bitrix\Main\UI\Extension::load("ui.buttons.icons");
        $addButton = new Bitrix\UI\Buttons\CreateButton();
        $addButton->addAttribute('onclick', 'BX.Ylab.Integrations.PopUp(\'add\').Show()');
        $addButton->addClass('ui-btn-icon-add');
        $addButton->setText(Loc::getMessage('BUTTON_ADD_INTEGRATION'));
        $addButton->setStyles(['float' => 'right']);

        return $addButton->render();
    }

}
