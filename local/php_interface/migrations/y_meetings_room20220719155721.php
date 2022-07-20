<?php

namespace Sprint\Migration;

use Bitrix\Main\Application;
use Bitrix\Main\DB\Exception;

class y_meetings_room20220719155721 extends Version
{
    protected $description = "Добавить поле MEET_DATE типа DateTime в таблицу y_meetings_room";

    protected $moduleVersion = "4.1.1";


    public function up()
    {
        $db = Application::getConnection();

        if ($db->isTableExists('y_meetings_room')) {
            $helper = $this->getHelperManager();
            $helper->Sql()->addColumnIfNotExists('y_meetings_room', 'MEET_DATE', 'DATETIME DEFAULT NULL');
        } else {
            throw new Exception('Таблица не существует');
        }
    }


    public function down()
    {
        $db = Application::getConnection();

        if ($db->isTableExists('y_meetings_room')) {
            $helper = $this->getHelperManager();
            $helper->Sql()->query('ALTER TABLE y_meetings_room DROP COLUMN MEET_DATE');
        } else {
            throw new Exception('Таблица не существует');
        }
    }
}
