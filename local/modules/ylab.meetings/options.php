<?php

/** @global CUser $USER */
/** @var CMain $APPLICATION */

if (!$USER->IsAdmin()) {
    return;
}

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$module_id = 'ylab.meetings';

Loc::loadMessages(__FILE__);

Loader::includeModule($module_id);


$request = Application::getInstance()->getContext()->getRequest();

$aTabs = [
    [
        "DIV" => "ylab_meetings_tab1",
        "TAB" => Loc::getMessage("YLAB_MEETINGS_SETTINGS"),
        "ICON" => "settings",
        "TITLE" => Loc::getMessage("YLAB_MEETINGS_TITLE"),
    ],
];

$aTabs[] = [
    'DIV' => 'rights',
    'TAB' => GetMessage('MAIN_TAB_RIGHTS'),
    'TITLE' => GetMessage('MAIN_TAB_TITLE_RIGHTS')
];

$arAllOptions = [
    'main' => [
        Loc::getMessage('YLAB_MEETINGS_SETTINGS_TITLE'),
        ['select_user', Loc::getMessage('YLAB_MEETINGS_SELECT_USER'), '', ['select_user']],
        Loc::getMessage('YLAB_MEETINGS_SETTINGS_TITLE_ZOOM'),
        ['client_id', Loc::getMessage('YLAB_MEETINGS_CLIENT_ID'), '', ['text', '70']],
        ['client_secret', Loc::getMessage('YLAB_MEETINGS_SECRET_CODE'), '', ['text', '70']],
        ['zoom_redirect_url', Loc::getMessage('YLAB_MEETINGS_URL_REDIRECT'), '', ['text', '70']],
    ],
];

if (($request->get('save') !== null || $request->get('apply') !== null) && check_bitrix_sessid()) {
    __AdmSettingsSaveOptions($module_id, $arAllOptions['main']);
    foreach ($arAllOptions["main"] as $arAllOption) {
        if ($arAllOption[3][0] == "select_user") {
            COption::SetOptionString($module_id, "select_user", implode(",", $_REQUEST["select_user"]));
        }
    }
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($module_id) ?>&lang=<?= LANGUAGE_ID ?>"
      name="ylab_meetings"><?
    echo bitrix_sessid_post();

    $tabControl->Begin();

    $tabControl->BeginNextTab();

    foreach ($arAllOptions["main"] as $key => $value) {
        if ($value[0] == "select_user") {
            ?>
            <tr>
                <td width="50%"
                    class="adm-detail-content-cell-l"><?= Loc::getMessage('YLAB_MEETINGS_SELECT_USER') ?></td>
                <td width="50%" class="adm-detail-content-cell-r">
                    <?
                    $selectedUserCodes = explode(',', COption::GetOptionString($module_id, $value[0]));
                    $APPLICATION->IncludeComponent(
                        'bitrix:main.user.selector',
                        '',
                        [
                            "ID" => "select_user_selector",
                            "LIST" => $selectedUserCodes,
                            "LAZYLOAD" => "Y",
                            "INPUT_NAME" => 'select_user[]',
                            "USE_SYMBOLIC_ID" => false,
                            "API_VERSION" => 2,
                            "SELECTOR_OPTIONS" => [
                            ]
                        ]
                    );
                    ?>
                </td>
            </tr>
            <?
        } else {
            __AdmSettingsDrawRow($module_id, $value);
        }
    }

    //__AdmSettingsDrawList($module_id, $arAllOptions["main"]);

    $tabControl->BeginNextTab();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php';

    $tabControl->Buttons([]);

    $tabControl->End();
    ?><input type="hidden" name="Update" value="Y"
</form>