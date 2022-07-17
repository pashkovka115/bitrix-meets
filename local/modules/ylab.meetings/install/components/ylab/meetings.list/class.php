<?php

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM;
use Bitrix\Main\UI\Filter\Options;


/**
 * Класс для отображения списков
 *
 * Class MeetingsListComponent
 * @package YLab\Components
 */
class MeetingsListComponent extends CBitrixComponent
{

    /** @var string $templateName Имя шаблона компонента */
    private $templateName;
    /** @var string $list_id Имя отображаемого списка */
    private $list_id;
    /** @var string $ormClaccName Имя класса ORM */
    private $ormClaccName;
    /** @var array $columnFields Набор полей колонок грида */
    private $columnFields;
    /** @var array $filterFields Набор полей доступных для фильтрации */
    private $filterFields;


    /**
     * Подготовка параметров компонента
     *
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {

        $this->templateName = $this->GetTemplateName();
        $this->list_id = $arParams['LIST_ID'];
        $this->ormClaccName = $arParams['ORM_NAME'];
        $this->columnFields = $arParams['COLUMN_FIELDS'];
        $this->filterFields = $arParams['FILTER_FIELDS'];

        return $arParams;
    }

    /**
     * Метод executeComponent
     *
     * @return mixed|void
     * @throws Exception
     */
    public function executeComponent()
    {
        if (CModule::IncludeModule('ylab.meetings')) {

            Loader::IncludeModule('ylab.meetings');

            if ($this->templateName == 'grid') {
                $this->showByGrid();
            } else if ($this->templateName == '' || $this->templateName == '.default') {
                $this->showByDefault();
            }

            $this->includeComponentTemplate();
        } else {

            echo Loc::getMessage('YLAB.MEETING.LIST.CLASS.MESSAGE');
            echo '<br>';
        }

    }

    /**
     * Отображение через дефолтный шаблон
     */
    public function showByDefault()
    {
        $this->arResult['ITEMS'] = $this->getAllElements();
        $this->arResult['GRID_HEAD'] = $this->getGridHead();
        $this->arResult['TABLE_NAME'] = $this->list_id;
    }

    /**
     * Отображение через грид
     */
    public function showByGrid()
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

            foreach ($this->columnFields as $k => $v) {
                if (is_numeric($k)) {
                    $arGridElement['data'][$v] = $arItem[$v];
                } else {
                    $arGridElement['data'][$k] = $arItem[$k];
                }

            }

            $arGridElement['actions'] = [
              [
                'text' => Loc::getMessage('YLAB.MEETING.LIST.CLASS.DELETE'),
                'onclick' => 'document.location.href="/' . $arItem['ID'] . '/delete/"'
              ],
              [
                'text' => Loc::getMessage('YLAB.MEETING.LIST.CLASS.EDIT'),
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
     */
    public function getAllElements(): array
    {
        $result = [];

        $ormNam = 'Ylab\Meetings\\' . $this->ormClaccName;

        $query = new ORM\Query\Query('Ylab\Meetings\\' . $this->ormClaccName);

        $elements = $query
          ->setFilter([])
          ->setSelect($this->columnFields)
          ->exec();

        return $elements->fetchAll();

    }

    /**
     * Получение элементов через ORM для шаблона grid
     *
     * @return array
     */
    public function getElements(): array
    {
        $result = [];

        $ormNam = 'Ylab\Meetings\\' . $this->ormClaccName;

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
        return 'ylab_meetings_' . $this->list_id;
    }

    /**
     * Возращает заголовки таблицы.
     *
     * @param array $columnFields
     * @return array
     */
    private function getGridHead(): array
    {

        // Формируем массивы с настройками
        $arrFieldNames = array();
        $arrRefElements = array();
        foreach ($this->columnFields as $key => $value) {
            if (is_numeric($key)) {
                $arrFieldNames[] = $value;
            } else {
                $pieces = explode(".", $value);
                $arrFieldNames[] = $pieces[0];
                $arrRefElements[$key] = $value;
            }
        }

        // Получаем имя вызываемого ORM класса
        $ormNam = 'Ylab\Meetings\\' . $this->ormClaccName;

        // Читаем ORM
        $mapObjects = $ormNam::getMap();

        // Массив заголовков для грида
        $gridHead = [];

        // Для каждого поля ORM класса проверяем присутствие его в параметрах нашего компонента
        // и в положительном случае записываем его в $gridHead
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
            else if (in_array($mapObject->getName(), $arrFieldNames) && $fieldTypes[4] == 'Relations') {

                $ormRefClaccNamePieses = explode("\\", $mapObject->getDataType());
                $ormRefClaccName = strtoupper($ormRefClaccNamePieses[3]);

                // Пробегаем по массиву с референсными элементами
                // и вытягиваем из рефернсной ORM необходимый 'title'
                foreach ($arrRefElements as $key => $value) {

                    $pieces = explode(".", $value);

                    if ($pieces[0] == $ormRefClaccName) {

                        $ormRefClassNam = $mapObject->getRefEntityName() . 'Table';

                        $mapRefObjects = $ormRefClassNam::getMap();

                        // делаем дополнительную проверку что поле указанное в параметрах есть в
                        // рефернсной ORM и получаем 'name' и 'title'
                        foreach ($mapRefObjects as $mapRefObject) {

                            if ($mapRefObject->getName() == $pieces[1]) {

                                $arr['id'] = $key;
                                $arr['name'] = $mapRefObject->getTitle();
                                $arr['type'] = $this->gridFilterDataType($mapRefObject->getDataType());
                                $arr['default'] = true;
                                $arr['sort'] = $key;

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

        return $gridHead;
    }

    /**
     * Возвращает тип поля для фильтрации.
     *
     * @param string $dataType
     * @return string
     */
    private function gridFilterDataType(string $dataType): string
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
    private function getGridFilterParams(): array
    {

        // Массив параметров фильтра
        $getGridFilterParams = [];
        foreach ($this->getGridHead() as $GridHeadElement) {

            $arr = [];

            if (in_array($GridHeadElement['id'], $this->filterFields)) {
                $arr['id'] = $GridHeadElement['id'];
                $arr['name'] = $GridHeadElement['name'];
                $arr['type'] = $GridHeadElement['type'];
            } else {
                continue;
            }

            array_push($getGridFilterParams, $arr);
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
            $this->gridNav->allowAllRecords(true)->setPageSize($this->getObGridParams()->GetNavParams()['nPageSize'])
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
        $obFilterOption = new Options($this->getGridId());
        $arFilterData = $obFilterOption->getFilter([]);
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
