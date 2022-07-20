<?php

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM;
use Bitrix\Main\UI\Filter\Options as FilterOptions;


/**
 * Класс для отображения списков
 *
 * Class MeetingsListComponent
 *
 */
class MeetingsListComponent extends CBitrixComponent
{

    /** @var string/null $templateName Имя шаблона компонента */
    private $templateName;
    /** @var string $listId Имя отображаемого списка */
    private string $listId;
    /** @var string $ormClassName Имя класса ORM */
    private string $ormClassName;
    /** @var array $columnFields Набор полей колонок грида */
    private array $columnFields;
    /** @var array $filterFields Набор полей доступных для фильтрации */
    private array $filterFields;
    /** @var ?PageNavigation $gridNav Параметры навигации грида */
    private ?PageNavigation $gridNav = null;


    /**
     * Метод executeComponent
     *
     * @return mixed|void|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {

        $this->templateName = $this->GetTemplateName();

        if (Loader::IncludeModule('ylab.meetings')) {

            $this->arResult['IS_MODULE_LOAD'] = true;

            if (is_string($this->arParams['LIST_ID'])) {
                $this->listId = $this->arParams['LIST_ID'];
            }
            if (is_string($this->arParams['ORM_NAME'])) {
                $this->ormClassName = $this->arParams['ORM_NAME'];
            }
            if (is_array($this->arParams['COLUMN_FIELDS'])) {
                $this->columnFields = $this->arParams['COLUMN_FIELDS'];
            }
            if (is_array($this->arParams['FILTER_FIELDS'])) {
                $this->filterFields = $this->arParams['FILTER_FIELDS'];
            }

            if ($this->templateName == 'grid') {
                if (!empty($this->columnFields) && !empty($this->ormClassName)) {

                    $this->showByGrid();
                }
            } else if ($this->templateName == '' || $this->templateName == '.default') {

                if (!empty($this->columnFields) && !empty($this->ormClassName)) {

                    $this->arResult['GRID'] = $this->getGridData();
                }
            }
        }

        $this->includeComponentTemplate();
    }


    /**
     * Массив для дефолтного шаблона
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getGridData(): array
    {

        $arr['GRID'] = [];

        $arr['GRID']['ITEMS'] = $this->getAllElements();
        $arr['GRID']['GRID_HEAD'] = $this->getGridHead();
        if (!empty($this->listId)) {
            $arr['GRID']['TABLE_NAME'] = $this->listId;
        }

        return $arr['GRID'];
    }


    /**
     * Отображение через грид
     */
    public function showByGrid(): void
    {

        $this->arResult['GRID_ID'] = $this->getGridId();
        $this->arResult['GRID_BODY'] = $this->getGridBody();
        $this->arResult['GRID_HEAD'] = $this->getGridHead();
        $this->arResult['GRID_NAV'] = $this->getGridNav();
        $this->arResult['GRID_FILTER'] = $this->getGridFilterParams();
        $this->arResult['RECORD_COUNT'] = $this->getGridNav()->getRecordCount();

    }


    /**
     * Возвращает содержимое (тело) таблицы.
     *
     * @return array
     */
    private function getGridBody(): array
    {
        $arBody = [];

        $arItems = $this->getElements();

        foreach ($arItems as $arItem) {
            $arGridElement = [];

            foreach ($this->columnFields as $columnField) {
                if (strpos($columnField, '.') != null) {
                    $pieces = explode(".", $columnField);
                    $aliasName = $pieces[0] . '_' . $pieces[1] . '_ALIAS';
                    $arGridElement['data'][$aliasName] = $arItem[$aliasName];

                } else {
                    $arGridElement['data'][$columnField] = $arItem[$columnField];
                }
            }

            $arGridElement['actions'] = [
              [
                'text' => Loc::getMessage('YLAB_MEETING_LIST_CLASS_DELETE'),
                'onclick' => 'document.location.href="/' . $arItem['ID'] . '/delete/"'
              ],
              [
                'text' => Loc::getMessage('YLAB_MEETING_LIST_CLASS_EDIT'),
                'onclick' => 'document.location.href="/' . $arItem['ID'] . '/edit/"'
              ],
            ];
            $arBody[] = $arGridElement;
        }

        return $arBody;
    }


    /**
     * Получение элементов через ORM для шаблона .default
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getAllElements(): array
    {
        $result = [];

        $columnFields = [];

        if (!empty($this->columnFields)) {

            foreach ($this->columnFields as $columnField) {
                if (!empty($columnField)) {
                    $columnFields[] = $columnField;
                }
            }

            $query = new ORM\Query\Query('Ylab\Meetings\\' . $this->ormClassName);

            $elements = $query
              ->setSelect($columnFields)
              ->exec();

            $result = $elements->fetchAll();

        }

        return $result;
    }


    /**
     * Получение элементов через ORM для шаблона grid
     *
     * @return array
     */
    public function getElements(): array
    {
        $result = [];

        if (!empty($this->columnFields)) {

            $columnFields = [];

            // Делаем проверку что параметр не пустой и формируем рефересный параметр
            foreach ($this->columnFields as $columnField) {
                if (!empty($columnField)) {
                    if (strpos($columnField, '.') != null) {
                        $pieces = explode(".", $columnField);
                        $aliasName = $pieces[0] . '_' . $pieces[1] . '_ALIAS';
                        $columnFields[$aliasName] = $columnField;
                    } else {
                        $columnFields[] = $columnField;
                    }

                }
            }

            $ormNam = 'Ylab\Meetings\\' . $this->ormClassName;

            $arCurSort = $this->getObGridParams()->getSorting(['sort' => ['ID' => 'DESC']])['sort'];
            $arFilter = $this->getGridFilterValues();

            $elements = $ormNam::GetList([
              'filter' => $arFilter,
              "count_total" => true,
              "offset" => $this->getGridNav()->getOffset(),
              "limit" => $this->getGridNav()->getLimit(),
              'order' => $arCurSort,
              'select' => $columnFields,
            ]);

            $this->getGridNav()->setRecordCount($elements->getCount());

            $result = $elements->fetchAll();

        }

        return $result;

    }


    /**
     * Возвращает идентификатор грида.
     *
     * @return string
     */
    private function getGridId(): string
    {
        $listId = "template_id";

        if (!empty($this->listId)) {
            $listId = $this->listId;
        }

        return 'ylab_meetings_' . $listId;
    }


    /**
     * Возращает заголовки таблицы.
     *
     * @return array
     */
    private function getGridHead(): array
    {
        // Массив заголовков для грида
        $gridHead = [];

        if (!empty($this->columnFields)) {

            // Формируем массивы с настройками
            $arrFieldNames = array();
            $arrRefElements = array();

            foreach ($this->columnFields as $columnField) {
                if (!empty($columnField)) {
                    if (strpos($columnField, '.') != null) {
                        $pieces = explode(".", $columnField);
                        $arrFieldNames[] = $pieces[0];
                        $arrRefElements[$pieces[0]] = $pieces[1];
                    } else {
                        $arrFieldNames[] = $columnField;
                    }
                }
            }


            // Получаем имя вызываемого ORM класса
            $ormNam = 'Ylab\Meetings\\' . $this->ormClassName;

            // Читаем ORM
            $mapObjects = $ormNam::getMap();

            foreach ($mapObjects as $mapObject) {

                $arr = [];
                $fieldTypes = explode("\\", get_class($mapObject));

                // Тип поля из ORM есть в настройках и он не референсный
                if (in_array($mapObject->getName(), $arrFieldNames) && $fieldTypes[4] != 'Relations') {

                    $arr['id'] = $mapObject->getName();
                    $arr['name'] = $mapObject->getTitle();
                    $arr['type'] = $this->gridFilterDataType($mapObject->getDataType());
                    $arr['default'] = true;
                    $arr['sort'] = $mapObject->getName();

                } // Тип поля из ORM есть в настройках и он референсный
                elseif (in_array($mapObject->getName(), $arrFieldNames) && $fieldTypes[4] == 'Relations') {

                    $ormRefClaccNamePieses = explode("\\", $mapObject->getDataType());
                    $ormRefClaccName = strtoupper($ormRefClaccNamePieses[3]);

                    // вытягиваем из рефернсной ORM необходимый 'name' и 'title'
                    foreach ($arrRefElements as $key => $value) {

                        if ($key == $ormRefClaccName) {

                            $ormRefClassNam = $mapObject->getRefEntityName() . 'Table';

                            $mapRefObjects = $ormRefClassNam::getMap();

                            // делаем дополнительную проверку что поле указанное в параметрах есть в
                            // рефернсной ORM и получаем 'name' и 'title'
                            foreach ($mapRefObjects as $mapRefObject) {

                                if ($mapRefObject->getName() == $value) {

                                    $arr['id'] = $key . '_' . $value . '_ALIAS';
                                    $arr['name'] = $mapRefObject->getTitle();
                                    $arr['type'] = $this->gridFilterDataType($mapRefObject->getDataType());
                                    $arr['default'] = true;
                                    $arr['sort'] = $key . '_' . $value . '_ALIAS';

                                }
                            }
                        }

                    }
                    // Если существующего поля ORM нет в настройках - не включаем его в $gridHead
                } else {
                    continue;
                }

                array_push($gridHead, $arr);
            }

        }

        return $gridHead;
    }


    /**
     * Возвращает тип поля для фильтрации.
     *
     * @param string $dataType
     * @return string
     */
    private
    function gridFilterDataType(string $dataType): string
    {

        if ($dataType == 'integer' || $dataType == 'float') {
            $gridFilterDataType = 'number';
        } else if ($dataType == 'data' || $dataType == 'datetime') {
            $gridFilterDataType = 'data';
        } else {
            $gridFilterDataType = 'string';
        }

        return $gridFilterDataType;
    }


    /**
     * Возвращает настройки отображения грид фильтра.
     *
     * @return array
     */
    private
    function getGridFilterParams(): array
    {

        // Массив параметров фильтра
        $getGridFilterParams = [];

        if (!empty($this->filterFields)) {

            $filterFields = [];

            foreach ($this->filterFields as $filterField) {
                if (!empty($filterField)) {
                    if (strpos($filterField, '.') != null) {
                        $pieces = explode(".", $filterField);
                        $aliasName = $pieces[0] . '_' . $pieces[1] . '_ALIAS';
                        $filterFields[] = $aliasName;
                    } else {
                        $filterFields[] = $filterField;
                    }
                }
            }

            foreach ($this->getGridHead() as $GridHeadElement) {

                $arr = [];

                if (in_array($GridHeadElement['id'], $filterFields)) {
                    $arr['id'] = $GridHeadElement['id'];
                    $arr['name'] = $GridHeadElement['name'];
                    $arr['type'] = $GridHeadElement['type'];
                } else {
                    continue;
                }

                array_push($getGridFilterParams, $arr);
            }

        }

        return $getGridFilterParams;
    }


    /**
     * Возвращает единственный экземпляр настроек грида.
     *
     * @return GridOptions
     */
    private
    function getObGridParams(): GridOptions
    {
        return $this->gridOption ?? $this->gridOption = new GridOptions($this->getGridId());

    }


    /**
     * Параметры навигации грида
     *
     * @return PageNavigation
     */
    private
    function getGridNav(): PageNavigation
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
    public
    function getGridFilterValues(): array
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
    public
    function prepareFilter(array $arFilterData, &$baseFilter = []): array
    {
        $arFilter = [];


        foreach ($this->getGridFilterParams() as $gridFilterParam) {

            if ($gridFilterParam['type'] == 'number') {

                if (!empty($arFilterData[$gridFilterParam['id'] . '_from'])) {
                    $arFilter['>=' . $gridFilterParam['id']] = (int)$arFilterData[$gridFilterParam['id'] . '_from'];
                }
                if (!empty($arFilterData[$gridFilterParam['id'] . '_to'])) {
                    $arFilter['<=' . $gridFilterParam['id']] = (int)$arFilterData[$gridFilterParam['id'] . '_to'];
                }
            }

            if ($gridFilterParam['type'] == 'data') {

                if (!empty($arFilterData[$gridFilterParam['id'] . '_from'])) {
                    $arFilter['>=' . $gridFilterParam['id']] = date(
                      "Y-m-d H:i:s",
                      strtotime($arFilterData[$gridFilterParam['id'] . '_from']));
                }
                if (!empty($arFilterData[$gridFilterParam['id'] . '_to'])) {
                    $arFilter['<=' . $gridFilterParam['id']] = date(
                      "Y-m-d H:i:s",
                      strtotime($arFilterData[$gridFilterParam['id'] . '_to']));
                }

            }

        }

        return $arFilter;
    }

}
