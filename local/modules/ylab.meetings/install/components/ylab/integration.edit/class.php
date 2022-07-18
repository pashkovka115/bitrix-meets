<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Ylab\Meetings\IntegrationTable;

/**
 * Class Component for edit and add integrations
 * @package ylab
 * @subpackage meetings
 */
class YlabIntegrationEditComponent extends CBitrixComponent
{
    /**
     * @throws \Bitrix\Main\LoaderException
     * @throws Exception
     */
    public function executeComponent()
    {
        Loader::includeModule('ylab.meetings');

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        //вызов формы добавления
        if ($request['step'] == 'addintegrationform') {
            $this->setTemplateName('edit');
            $this->includeComponentTemplate('addintegrationform');
            return;
        }
        // добавление и редактирование формах
        switch ($request['step']) {
            case 'addintegration':
                $fields = [
                    'NAME' => $request['NAME'],
                    'ACTIVITY' => $request['ACTIVITY'] === 'Y',
                    'INTEGRATION_REF' => $request['INTEGRATION_REF'],
                    'LOGIN' => $request['LOGIN'],
                    'PASSWORD' => $request['PASSWORD']
                ];
                $submitResult = $this->addIntegration($fields);
                if ($submitResult->isSuccess()) {
                    $integration = $submitResult;
                    $this->arResult['ADD_SUCCESS_INTEGRATION_NAME'] = $integration->getData()['NAME'];
                    //вызов главного шаблона с передачей сообщения об успешном добавлении интеграции
                    $this->includeComponentTemplate();
                    return;
                } else {
                    $this->arResult['SUBMIT_ERROR'] = $submitResult->getErrorMessages();
                    //вызов шаблона с форма с передачей сообщений об ошибке добавления
                    $this->setTemplateName('edit');
                    $this->includeComponentTemplate('addintegrationform');
                    return;
                }
                break;

            case 'editintegration': {
                    $fields[$request['ID']] = [
                        'NAME' => $request['NAME'],
                        'ACTIVITY' => $request['ACTIVITY'] === 'Y',
                        'INTEGRATION_REF' => $request['INTEGRATION_REF'],
                        'LOGIN' => $request['LOGIN'],
                        'PASSWORD' => $request['PASSWORD']
                    ];
                    $submitResult = $this->editIntegration($fields);
                    if ($submitResult->isSuccess()) {
                        $integration = $submitResult;
                        $this->arResult['ADD_SUCCESS_INTEGRATION_NAME'] = $integration->getData()['NAME'];
                        //вызов главного шаблона с передачей сообщения об успешном добавлении интеграции
                        $this->includeComponentTemplate();
                        return;
                    } else {
                        $this->arResult['SUBMIT_ERROR'] = $submitResult->getErrorMessages();
                        $this->arResult['ID'] = $request['ID'];
                        //вызов шаблона с форма с передачей сообщений об ошибке добавления
                        $this->setTemplateName('edit');
                        $this->includeComponentTemplate('editintegrationform');
                        return;
                    }
                    break;
                }
        }

        $requestData = $request->getValues();

        // бутерброд
        switch ($requestData['op']) {
            case 'delete':
                $this->deleteIntegration($requestData['id']);
                $this->includeComponentTemplate();
                break;
            case 'edit':

                $this->arResult['ID'] = $requestData['id'];
                $this->setTemplateName('edit');
                $this->includeComponentTemplate('editintegrationform');
                return;
                break;
        }

        // нижняя панель действий
        switch ($requestData['action_button_integration_list']) {
            case 'delete':
                $this->deleteIntegration($requestData['ID']);
                $this->includeComponentTemplate();
                break;
            case 'edit':
                $this->editIntegration($requestData['FIELDS']);
                $this->includeComponentTemplate();
                break;
        }
        $this->includeComponentTemplate();
    }

    /**
     * @param array $fields
     * @return \Bitrix\Main\ORM\Data\AddResult
     * @throws Exception
     */
    private
    function addIntegration(array $fields): \Bitrix\Main\ORM\Data\AddResult
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

    /**
     * @param $id
     * @return \Bitrix\Main\ORM\Data\DeleteResult
     * @throws Exception
     */
    private
    function deleteIntegration($id): \Bitrix\Main\ORM\Data\DeleteResult
    {
        /** @var Bitrix\Main\Entity\UpdateResult $result */
        if (is_array($id)) {
            foreach ($id as $item)
                $result = IntegrationTable::delete($item);
        } else {
            $result = IntegrationTable::delete($id);
        }
        return $result;
    }

    /**
     * @param $fields
     * @return \Bitrix\Main\Entity\UpdateResult|\Bitrix\Main\ORM\Data\UpdateResult
     * @throws Exception
     */
    private
    function editIntegration($fields)
    {
        /** @var Bitrix\Main\Entity\UpdateResult $result */
        foreach ($fields as $id => $f)
            $result = IntegrationTable::update($id, array(
                'NAME' => $f['NAME'],
                'ACTIVITY' => $f['ACTIVITY'],
                'INTEGRATION_REF' => $f['INTEGRATION_REF'],
                'LOGIN' => $f['LOGIN'],
                'PASSWORD' => $f['PASSWORD']
            ));

        return $result;
    }
}
