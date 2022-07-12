<?php

namespace YLab\Components;

use \Bitrix\Main\Grid\Options as GridOptions;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\UI\PageNavigation;
use \Ylab\Meetings\RoomTable;
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

    private $list_id = 'rooms_list';


  /**
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
//        Loader::includeModule('iblock');

        $this->templateName = $this->GetTemplateName();

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
    $this->arResult['GRID_ID'] = $this->list_id;

    $this->arResult['GRID_BODY'] = $this->getGridBody();
    $this->arResult['GRID_HEAD'] = $this->getGridHead();

    $this->arResult['GRID_NAV'] = $this->getGridNav();
//    $this->arResult['GRID_FILTER'] = $this->getGridFilterParams();

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

      $arGridElement['data'] = [
        'ID' => $arItem['ID'],
        'NAME' => $arItem['NAME'],
        'ACTIVITY ' => $arItem['ACTIVITY '],
        'INTEGRATION_ID' => $arItem['INTEGRATION_ID'],
      ];

      $arGridElement['actions'] = [
        [
          'text' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.DELETE'),
          'onclick' => 'document.location.href="/'.$arItem['ID'].'/edit/"'
//                'onclick' => 'if(confirm("Точно?")){document.location.href="?op=delete&id='.$arItem['ID'].'"}'
        ],
        [
          'text' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.EDIT'),
          'onclick' => 'document.location.href="/'.$arItem['ID'].'/edit/"'
//                  'onclick' => "jsUtils.Redirect(arguments, '/bitrix/admin/user_edit.php')",
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
    $result = [];
    $grid_options = new GridOptions($this->list_id);
    $sort = $grid_options->GetSorting(['sort' => ['ID' => 'ASC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
    $nav_params = $grid_options->GetNavParams();


    $nav = new PageNavigation('request_list');
    $nav->allowAllRecords(true)
      ->setPageSize($nav_params['nPageSize'])
      ->initFromUri();

    $filterOption = new \Bitrix\Main\UI\Filter\Options($this->list_id);
    $filterData = $filterOption->getFilter([]);
    $filter = [];

    foreach ($filterData as $k => $v) {
      $filter['NAME'] = "%".$filterData['FIND']."%";
    }

    Loader::includeModule('ylab.meetings');

    $query = new ORM\Query\Query('RoomTable');
//$query = new ORM\Query\Query($customEntity);

    $result = $query
      ->setFilter([

      ])
      ->setSelect([
        'ID', 'NAME', 'ACTIVITY', 'INTEGRATION_ID'
      ])
      ->exec();



//    $result = RoomTable::getList([
//      'filter' => $filter,
//      'select' => [
//        "*",
//      ],
//      'offset'      => $nav->getOffset(),
//      'limit'       => $nav->getLimit(),
//      'order'       => $sort['sort']
//    ]);

    return $result;
  }

  /**
   * Параметры навигации грида
   *
   * @return PageNavigation
   */
  private function getGridNav(): PageNavigation
  {

    $grid_options = new GridOptions($this->list_id);
    $nav_params = $grid_options->GetNavParams();

    $nav = new PageNavigation('request_list');
    $nav->allowAllRecords(true)
      ->setPageSize($nav_params['nPageSize'])
      ->initFromUri();

    return $nav;
  }


  /**
   * Возращает заголовки таблицы.
   *
   * @return array
   */
  private function getGridHead(): array
  {
    return [
      [
        'id' => 'ID',
        'name' => 'ID',
        'default' => true,
        'sort' => 'ID',
      ],
      [
        'id' => 'NAME',
//                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.NAME'),
        'name' => 'Название переговорной',
        'default' => true,
        'sort' => 'PROPERTY_NAME',
      ],
      [
        'id' => 'ACTIVITY',
//                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.EMAIL'),
        'name' => 'Активность',
        'default' => true,
      ],
      [
        'id' => 'INTEGRATION_ID',
//                'name' => Loc::getMessage('MYLAB.EMAIL.LIST.CLASS.CITY'),
        'name' => 'Интеграция',
        'default' => true,
        'sort' => 'PROPERTY_INTEGRATION_ID',
      ],
    ];
  }

}
