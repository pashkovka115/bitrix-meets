<?php

namespace Ylab\Meetings\Integrations;

use Ylab\Meetings\IntegrationTable;

abstract class IntegrationBase
{
    /**
     * @param $id_integration
     * @return false|object
     * Инициализирует объект интеграции и возвращает его
     */
    public static function init($id_integration)
    {
        $intteg = IntegrationTable::getRowById($id_integration);

        if ($intteg){
            $class = $intteg['INTEGRATION_REF'];
            return new $class();
        }

        return false;
    }

    abstract public function getLink();
}