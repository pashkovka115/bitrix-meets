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
        ['note' => Loc::getMessage('YLAB_MEETINGS_SETTINGS_TITLE')],
        ['client_id', Loc::getMessage('YLAB_MEETINGS_CLIENT_ID'), '', ['text', '70']],
        ['client_secret', Loc::getMessage('YLAB_MEETINGS_SECRET_CODE'), '', ['text', '70']],
        ['zoom_redirect_url', Loc::getMessage('YLAB_MEETINGS_URL_REDIRECT'), '', ['text', '70']],
    ],
];

if (($request->get('save') !== null || $request->get('apply') !== null) && check_bitrix_sessid()) {
    __AdmSettingsSaveOptions($module_id, $arAllOptions['main']);
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>
<form method="post"
      action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($module_id) ?>&lang=<?= LANGUAGE_ID ?>"
      name="ylab_meetings"><?
    echo bitrix_sessid_post();

    $tabControl->Begin();

    $tabControl->BeginNextTab();

    __AdmSettingsDrawList($module_id, $arAllOptions["main"]);

    $tabControl->BeginNextTab();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php';

    $tabControl->Buttons([]);

    $tabControl->End();
    ?><input type="hidden" name="Update" value="Y"
</form>