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
