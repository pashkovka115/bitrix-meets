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

            $action = $this->arParams['ACTION'];
            if ($action['NAME'] == 'add') {
                $this->setTemplateName('edit');
                $this->includeComponentTemplate('addintegrationform');
                return;
            }
            if ($action['NAME'] == 'submitadd') {
                $addResult = $this->addIntegration($action['FIELDS']);
                if (!$addResult->isSuccess()) {
                    $this->arResult['SUBMIT_ERROR'] = $addResult->getErrorMessages();
                    $this->setTemplateName('edit');
                    $this->includeComponentTemplate('addintegrationform');
                    return;
                } else {
                    $this->arResult['ADD_SUCCESS_INTEGRATION_NAME'] = $addResult->getData()['NAME'];
                }
            }
            if ($action['NAME'] == 'edit_burger') {
                $this->fillEditFields($action['ID']);
                $this->setTemplateName('edit');
                $this->includeComponentTemplate('editintegrationform');
                return;
            }

            if ($action['NAME'] == 'submitedit') {
                $editResult = $this->editIntegration($action['FIELDS']);
                if (!$editResult->isSuccess()) {
                    $this->arResult['SUBMIT_ERROR'] = $editResult->getErrorMessages();

                    $this->fillEditFields(key($action['FIELDS']));
                    $this->setTemplateName('edit');
                    $this->arResult['ID'] = key($action['FIELDS']);
                    $this->includeComponentTemplate('editintegrationform');
                    return;
                }
            }

            if ($action['NAME'] == 'delete_burger') {
                $this->deleteIntegration($action['ID']);
            }

            $this->showByGrid();
            $this->includeComponentTemplate();

        }
    }

    /**
     * Отображение через грид
     * Получение в $arResult параметров для грида и кнопок
     */
    public function showByGrid(): void
    {

        $this->arResult['GRID_ID'] = $this->getGridId();
        AddMessage2Log($this->getGridId());
        $this->arResult['GRID_COLUMNS'] = $this->getGridColumns();
        $this->arResult['GRID_ROWS'] = $this->getGridRows();
        $this->arResult['GRID_NAV'] = $this->getGridNav();
        $this->arResult['GRID_FILTER'] = $this->getGridFilterParams();
        $this->arResult['RECORD_COUNT'] = $this->getGridNav()->getRecordCount();

        $this->arResult['BUTTONS']['ADD'] = $this->getAddButton();
        $this->arResult['BUTTONS']['ACTION'] = $this->getActionPanelButtons();
        $this->arResult['AJAX_PATH'] = $this->getAjaxPath();
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
                    'onclick' => 'BX.Ylab.Integrations.LeftPanel.action(' .
                        CUtil::PhpToJSObject([
                            'action' => 'delete_burger',
                            'id' => $arItem['ID'],
                        ]) . ')'
                ],
                [
                    'text' => Loc::getMessage('YLAB_MEETING_LIST_CLASS_EDIT'),
                    'onclick' => 'BX.Ylab.Integrations.Grid.LeftPanel.create(' . CUtil::PhpToJSObject($this->getAjaxPath()) . ', ' .
                        CUtil::PhpToJSObject([
                            'sessid' => bitrix_sessid(),
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
        $addButton->addAttribute('onclick', 'popup().Show()');
        $addButton->addClass('ui-btn-icon-add');
        $addButton->setText(Loc::getMessage('BUTTON_ADD_INTEGRATION'));
        $addButton->setStyles(['float' => 'right']);

        return $addButton->render();
    }

    /**
     * Метод возвращающий кнопки для панели действий грида
     * TODO: использовать BX.Ajax
     *
     * @return array
     */
    public function getActionPanelButtons(): array
    {
        $snippets = new \Bitrix\Main\Grid\Panel\Snippet();
        $removeButton = $snippets->getRemoveButton();
        $editButton = $snippets->getEditButton();
        return ['EDIT' => $editButton, 'REMOVE' => $removeButton];
    }

    /**
     * Возвращает ссылку на ajax.php файла в папке компонента
     *
     * @return string
     */
    public function getAjaxPath(): string
    {
        return $this->getPath() . '/ajax.php';
    }

    private function editIntegration($fields)
    {
        /** @var Bitrix\Main\Entity\UpdateResult $result */
        foreach ($fields as $id => $f)
            $result = IntegrationTable::update($id, array(
                'NAME' => $f['NAME'],
                'ACTIVITY' => $f['ACTIVITY'],
                'INTEGRATION_REF' => $f['INTEGRATION_REF'],
                'LOGIN' => $f['LOGIN'],
                'PASSWORD' => $f['PASSWORD']
            ));

        return $result;
    }

    private function deleteIntegration($id): \Bitrix\Main\ORM\Data\DeleteResult
    {
        /** @var Bitrix\Main\Entity\UpdateResult $result */
        if (is_array($id)) {
            foreach ($id as $item)
                $result = IntegrationTable::delete($item);
        } else {
            $result = IntegrationTable::delete($id);
        }
        return $result;
    }

    private function fillEditFields($id)
    {
        $res = Ylab\Meetings\IntegrationTable::getList([
            'filter' => ['ID' => $id],
            'select' => [
                "*",
            ]]);
        foreach ($res->fetchAll() as $row) {
            $this->arResult["ID"] = $row['ID'];
            $this->arResult["NAME"] = $row['NAME'];
            $this->arResult["ACTIVITY"] = $row['ACTIVITY'];
            $this->arResult["INTEGRATION_REF"] = $row['INTEGRATION_REF'];
            $this->arResult["LOGIN"] = $row['LOGIN'];
            $this->arResult["PASSWORD"] = $row['PASSWORD'];
        }
    }
}
