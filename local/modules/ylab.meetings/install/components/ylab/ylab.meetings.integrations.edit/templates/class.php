<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader;
use Ylab\Meetings\IntegrationTable;

/**
 * Class Component for edit and add integrations
 * @package ylab
 * @subpackage meetings
 */
class YlabMeetingsIntegrationsEditComponent extends CBitrixComponent
{
	/**
	 * @return mixed|void|null
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function executeComponent()
	{
		Loader::includeModule('ylab.meetings');

		$this->includeComponentTemplate();
	}

	private function addIntegration(array $fields): bool
	{
		/** @var int|false $result */
		$result = IntegrationTable::add(array(
			'NAME' => '',
			'ACTIVITY' => '',
			'INTEGRATION_REF' => '',
			'LOGIN' => '',
			'PASSWORD' => ''
		));

		return $result;
	}

	private function editIntegration($id): bool
	{
		/** @var Bitrix\Main\Entity\UpdateResult $result */
		$result = IntegrationTable::update($id, array(
			'NAME' => '',
			'ACTIVITY' => '',
			'INTEGRATION_REF' => '',
			'LOGIN' => '',
			'PASSWORD' => ''
		));

		return $result->isSuccess();
	}

	private function deleteIntegration($id): bool
	{
		/** @var Bitrix\Main\Entity\UpdateResult $result */
		$result = IntegrationTable::delete($id);

		return $result->isSuccess();
	}
}
