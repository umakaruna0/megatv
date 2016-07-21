<?php

namespace Sprint\Migration;

class Version20160720204216 extends Version {

    protected $description = "Увеличить размер типа текстового для статистики по пользователям.";

    public function up()
    {
        global $DB;
        $strSql = 'ALTER TABLE hw_user_stat MODIFY UF_RECOMMEND LONGTEXT;';
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        
        $this->outSuccess('Тип для хранения рекомендации изменен успешно!');
    }

    public function down()
    {
        
    }
}
