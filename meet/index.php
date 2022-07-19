<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("meet");
?>

<?$APPLICATION->IncludeComponent("ylab:meeting.detail", "",
    Array(
        "ID" => 2
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
