<?php

namespace Sprint\Migration;

use Bitrix\Main\Application;
use Bitrix\Main\DB\Exception;

class y_meetings_room20220721090814 extends Version
{
    protected $description = "Добавление поля со ссылкой на тип календаря";

    protected $moduleVersion = "4.1.1";
    protected $tableName = 'y_meetings_room';


    public function up()
    {
        $db = Application::getConnection();

        if ($db->isTableExists($this->tableName)) {
            $helper = $this->getHelperManager();

            $column = $helper->Sql()->getColumn($this->tableName, 'CALENDAR_TYPE_XML_ID');
            if (empty($column)) {
                $helper->Sql()->query("ALTER TABLE $this->tableName
                                          ADD COLUMN CALENDAR_TYPE_XML_ID VARCHAR(255) NULL DEFAULT NULL,
                                          ADD CONSTRAINT y_meetings_room_calendar_type_xml_id
                                              FOREIGN KEY (CALENDAR_TYPE_XML_ID) REFERENCES b_calendar_type(XML_ID)
                                          ON DELETE CASCADE");
            }
        } else {
            throw new Exception('Таблица не существует');
        }
    }


    public function down()
    {
        $db = Application::getConnection();

        if ($db->isTableExists($this->tableName)) {
            $helper = $this->getHelperManager();

            $column = $helper->Sql()->getColumn($this->tableName, 'CALENDAR_TYPE_XML_ID');
            if (!empty($column)){
                $helper->Sql()->query("ALTER TABLE $this->tableName DROP FOREIGN KEY y_meetings_room_calendar_type_xml_id");
                $helper->Sql()->query("ALTER TABLE $this->tableName DROP COLUMN CALENDAR_TYPE_XML_ID");
            }
        } else {
            throw new Exception('Таблица не существует');
        }
    }
}
