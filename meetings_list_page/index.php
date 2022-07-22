<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"ylab:meetings.list",
	"grid",
	Array(
		"COLUMN_FIELDS" => array("ID","NAME","ACTIVITY","INTEGRATION.NAME",""),
		"FILTER_FIELDS" => array("ID","NAME","ACTIVITY",""),
		"LIST_ID" => "rooms_list",
		"ORM_NAME" => "RoomTable"
	)
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>