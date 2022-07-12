<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
    die();
}
use Bitrix\Main\Localization\Loc;

?>
<select id="select" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
    <?foreach($arResult as $arItems):?>  <!-- генерация доступных переговорных -->
        <option value="<?=$arItems["ID"]?>"><?=$arItems["NAME"]?></option>
    <?endforeach;?>
</select>
<hr>
<?foreach($arResult as $arItems):?>  <!-- генерация блоков с календарем для каждой комнаты -->
    <div id="<?=$arItems["ID"]?>" class="select-blocks" style="display:none">
        <h2><?= Loc::getMessage('YLAB.SHOW.ROOMS.CALENDAR')?> <?=$arItems["NAME"]?></h2>  <!-- вывод названия комнаты -->
        <?$APPLICATION->IncludeComponent("bitrix:news.calendar","",Array(   //вывод календаря
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "news",
                "IBLOCK_ID" => "3",
                "MONTH_VAR_NAME" => "month",
                "YEAR_VAR_NAME" => "year",
                "WEEK_START" => "1",
                "DATE_FIELD" => "DATE_ACTIVE_FROM",
                "TYPE" => "EVENTS",
                "SHOW_YEAR" => "Y",
                "SHOW_TIME" => "Y",
                "TITLE_LEN" => "0",
                "SET_TITLE" => "Y",
                "SHOW_CURRENT_DATE" => "Y",
                "SHOW_MONTH_LIST" => "Y",
                "NEWS_COUNT" => "0",
                "DETAIL_URL" => "",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => ""
            )
        );?>
    </div>
<?endforeach;?>


<!-- скрипт вывода блока в зависимости от выбранного option -->
<script>
    $(function() {
        $("#" + $("#select option:selected").val()).show();  //активировали выбранный блок
        $("#select").change(function(){  //поймали событие изменение формы
            $(".select-blocks").hide();  //скрыли активный блок
            $("#" + $(this).val()).show();  //активировали выбранный блок
        });
    });
</script>
