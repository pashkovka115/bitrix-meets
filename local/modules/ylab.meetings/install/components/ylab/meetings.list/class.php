<?php

namespace YLab\Components;

use \Bitrix\Main\Grid\Options as GridOptions;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Seo\Engine\Bitrix;
use Ylab\Meetings\IntegrationTable;
use Ylab\Meetings\RoomTable;
use \Bitrix\Main\Loader;
use \CBitrixComponent;
use \Exception;
use \Bitrix\Main\ORM;
use \Bitrix\Main\UI\Filter\Options;
use Bitrix\Main\ORM\Fields\IntegerField;

use \Bitrix\Main\Entity\Query;


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
  /** @var array $filterFields Набор полей колонок грида */
  private $filterFields;


  /**
   * @param $arParams
   * @return array
   * @throws \Bitrix\Main\LoaderException
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
    Loader::includeModule('ylab.meetings');

    if ($this->templateName == 'grid') {
      $this->showByGrid();
    }

    $this->includeComponentTemplate();
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

      foreach ($this->columnFields as $k => $v)
      {
          $arGridElement['data'][$v] = $arItem[$v];
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
   * Получение элементов ORM
   *
   * @return ORM\Query\Result
   */
  public function getElements(): ORM\Query\Result
  {

    $query = new ORM\Query\Query('Ylab\Meetings\\' . $this->ormClaccName);

    $queryResult = $query
      ->setFilter([])
      ->setSelect($this->columnFields)
      ->exec();

    return $queryResult;

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

    $ormNam = 'Ylab\Meetings\\' . $this->ormClaccName;
    $mapObjects = $ormNam::getMap();

    $gridHead = [];
    foreach ($mapObjects as $mapObject) {

      $arr = [];

      if (in_array($mapObject->getName(), $this->columnFields)) {

        $arr['id'] = $mapObject->getName();
        $arr['name'] = $mapObject->getTitle();
        $arr['default'] = true;

      }
      array_push($gridHead, $arr);
    }

    return $gridHead;

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

}
