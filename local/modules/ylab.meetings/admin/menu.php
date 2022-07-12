<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

AddEventHandler('main', 'OnBuildGlobalMenu', 'YlabMeetingsModuleMenu');

if (!function_exists('YlabMeetingsModuleMenu')) {
    /**
     * Отображение меню
     * @param $adminMenu
     * @param $moduleMenu
     */
    function YlabMeetingsModuleMenu(&$adminMenu, &$moduleMenu)
    {
        $adminMenu['global_menu_services']['items'][] = [
            'section' => 'ylab-meetings-pages',
            'sort' => 110,
            'text' => Loc::getMessage('YLAB_MEETINGS_TITLE_PAGE'),
            'items_id' => 'nlmk-hidden-pages',
            'items' => [
                [
                    'parent_menu' => 'ylab-meetings-pages',
                    'section' => 'ylab-meetings-pages-rooms',
                    'sort' => 500,
                    'url' => 'ylab.meetings_rooms.php?lang=' . LANG,
                    'text' => Loc::getMessage('YLAB_MEETING_ROOMS_PAGE'),
                    'title' => Loc::getMessage('YLAB_MEETING_ROOMS_PAGE'),
                    'icon' => 'form_menu_icon',
                    'page_icon' => 'form_page_icon',
                    'items_id' => 'ylab-meetings-pages-rooms'
                ],
                [
                    'parent_menu' => 'ylab-meetings-pages',
                    'section' => 'ylab-meetings-pages-integrations',
                    'sort' => 500,
                    'url' => 'ylab.meetings_integrations.php?lang=' . LANG,
                    'text' => Loc::getMessage('YLAB_MEETING_INTEGRATIONS_PAGE'),
                    'title' => Loc::getMessage('YLAB_MEETING_INTEGRATIONS_PAGE'),
                    'icon' => 'form_menu_icon',
                    'page_icon' => 'form_page_icon',
                    'items_id' => 'ylab-meetings-pages-integrations'
                ]
            ]
        ];
    }

}
