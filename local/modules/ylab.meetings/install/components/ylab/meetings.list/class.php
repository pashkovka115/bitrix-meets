<?php

namespace YLab\Components;

use \Bitrix\Main\Grid\Options as GridOptions;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\UI\PageNavigation;
use Ylab\Meetings\RoomTable;
use \Bitrix\Main\Loader;
use \CBitrixComponent;
use \Exception;
use \Bitrix\Main\ORM;
//use \Bitrix\Main\UI\Filter\Options;

/**
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
  /** @var array $ormClaccName Набор полей колонок грида */
  private $columnFields;


  /**
   * @param $arParams
   * @return array
   * @throws \Bitrix\Main\LoaderException
   */
  public function onPrepareComponentParams($arParams)
  {

    $this->templateName = $this->GetTemplateName();
    $this->list_id = $arParams['LIST_ID'];
    $this->ormClaccName = $arParams['ORM_NAME'];

    $this->columnFields = $arParams['COLUMN_FIELDS'];

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
    $this->arResult['GRID_HEAD'] = $this->getGridHead($this->columnFields);
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

      foreach ($this->columnFields as $k => $v) {
        if(is_numeric($k)) {
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
   * Получим элементы ORM
   * @return
   */
  public function getElements()
  {

    Loader::includeModule('ylab.meetings');

    $query = new ORM\Query\Query('Ylab\Meetings\\'. $this->ormClaccName);

    $result = $query
      ->setFilter([])
      ->setSelect($this->columnFields)
      ->exec();

    return $result;
  }

  /**
   * Параметры навигации грида
   *
   * @return PageNavigation
   */
  private function getGridNav(): PageNavigation
  {

    $grid_options = new GridOptions($this->getGridId());
    $nav_params = $grid_options->GetNavParams();

    $nav = new PageNavigation('request_list');
    $nav->allowAllRecords(true)
      ->setPageSize($nav_params['nPageSize'])
      ->initFromUri();

    return $nav;
  }

  /**
   * Возвращает идентификатор грида.
   *
   * @return string
   */
  private function getGridId(): string
  {
    return 'ylab_meetings_list_' . $this->list_id;
  }

  /**
   * Возращает заголовки таблицы.
   *
   * @return array
   */
  private function getGridHead(array $columnFields): array
  {
    $gridHead = [];
    foreach ($columnFields as $k => $v) {
      $arr = [];
      if(is_numeric($k)) {
        $arr['id'] = $v;
        $arr['name'] = $v;
        $arr['default'] = true;
      } else {
        $arr['id'] = $k;
        $arr['name'] = $k;
        $arr['default'] = true;
      }
      array_push( $gridHead, $arr );
    }

//    echo '<pre>';
//    print_r($gridHead);
//    echo '<pre>';

    return $gridHead;

//    return [
//      [
//        'id' => 'ID',
//        'name' => 'ID',
//        'default' => true,
//        'sort' => 'ID',
//      ],
//      [
//        'id' => 'NAME',
////                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.NAME'),
//        'name' => 'Название переговорной',
//        'default' => true,
//      ],
//      [
//        'id' => 'ACTIVITY',
////                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.EMAIL'),
//        'name' => 'Активность',
//        'default' => true,
//      ],
//      [
//        'id' => 'INTEGRATION_ALIAS',
////                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.CITY'),
//        'name' => 'Интеграция',
//        'default' => true,
//      ],
//    ];
  }

}
