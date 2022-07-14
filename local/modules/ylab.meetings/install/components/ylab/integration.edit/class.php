<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ylab\Meetings\IntegrationTable;
use Bitrix\Main\ORM\Data\DataManager;

/**
 * Class Component for edit and add integrations
 * @package ylab
 * @subpackage meetings
 */
class YlabIntegrationsEditComponent extends CBitrixComponent
{

    /**
     * @return mixed|void|null
     * @throws Exception
     */
    public function executeComponent()
    {
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        switch ($request["step"]) {
            case NULL:
                $this->includeComponentTemplate();
                break;
            case 'addintegrationform':
                $this->includeComponentTemplate('addintegration');
                break;
            case 'addintegration':
                $fields['NAME'] = $request['NAME'];
                $fields['ACTIVITY'] = $request['ACTIVITY'] === 'Y';
                $fields['INTEGRATION_REF'] = $request['INTEGRATION_REF'];
                $fields['LOGIN'] = $request['LOGIN'];
                $fields['PASSWORD'] = $request['PASSWORD'];

                $submitResult = $this->addIntegration($fields);
                if ($submitResult->isSuccess()) {
                    $integration = $submitResult;
                    $this->arResult['ADD_SUCCESS_INTEGRATION_NAME'] = $integration->getData()['NAME'];
                    //вызов главного шаблона с передачей сообщения об успешном добавлении интеграции
                    $this->includeComponentTemplate();
                } else {
                    $this->arResult['SUBMIT_ERROR'] = $submitResult->getErrorMessages();
                    //вызов шаблона с форма с передачей сообщений об ошибке добавления
                    $this->includeComponentTemplate('addintegration');
                }
                break;
        }
    }


    /**
     * @param array $fields
     * @return \Bitrix\Main\ORM\Data\AddResult
     * @throws Exception
     */
    private
    function addIntegration(array $fields)
    {

        $result = IntegrationTable::add(array(
            'NAME' => $fields['NAME'],
            'ACTIVITY' => $fields['ACTIVITY'],
            'INTEGRATION_REF' => $fields['INTEGRATION_REF'],
            'LOGIN' => $fields['LOGIN'],
            'PASSWORD' => $fields['PASSWORD']
        ));

        return $result;
    }

    //Предположительная реализация методов для редактирования и удаления интеграций (до стыковки с гридами)
    /*
        private
        function editIntegration($id): bool
        {
            /** @var Bitrix\Main\Entity\UpdateResult $result *//*
        $result = IntegrationTable::update($id, array(
            'NAME' => '',
            'ACTIVITY' => '',
            'INTEGRATION_REF' => '',
            'LOGIN' => '',
            'PASSWORD' => ''
        ));

        return $result->isSuccess();
    }
*/
    /*
    private
    function deleteIntegration($id): bool
    {
        /** @var Bitrix\Main\Entity\UpdateResult $result *//*
        $result = IntegrationTable::delete($id);

        return $result->isSuccess();
    }*/
}
