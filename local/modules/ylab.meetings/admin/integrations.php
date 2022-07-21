<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin.php';

global $APPLICATION;
$APPLICATION->IncludeComponent(
	'ylab:meetings.integrations.list',
	'grid',
	[
		'LIST_ID' => 'integrations_list',
		'ORM_NAME' => 'IntegrationTable',
		'COLUMN_FIELDS' => array(
			0 => 'ID',
			1 => 'NAME',
			2 => 'ACTIVITY',
			3 => 'INTEGRATION_REF',
			4 => 'LOGIN',
			5 => 'PASSWORD',
		),
		'FILTER_FIELDS' => array(
			0 => 'ID',
			1 => 'NAME',
			2 => 'ACTIVITY',
			3 => 'INTEGRATION_REF',
			4 => 'LOGIN',
			5 => 'PASSWORD',
		)
	]
);