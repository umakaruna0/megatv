<?php
namespace Sprint\Migration;

class Version20160715104044 extends Version {

    protected $description = "Символьный код для программ с учетом повтора epg_id";

    public function up()
    {
        global $APPLICATION;
        $arProgs = array();
        $codes = array();
        $result = \Hawkart\Megatv\ProgTable::getList(array(
            'filter' => array(),
            'select' => array("ID", "UF_TITLE", "UF_CODE", "UF_EPG_ID")
        ));
        while ($row = $result->fetch())
        {
            $arParams = array("replace_space"=>"-", "replace_other"=>"-");
            $code = \CDev::translit(trim($row["UF_TITLE"]), "ru", $arParams);
            
            if(!empty($row["UF_CODE"]))
            {
                $codes[$row["UF_TITLE"]] = $row;
                continue;
            }
            
            $arCode = $codes[$row["UF_TITLE"]];
            if(!empty($arCode))
            {
                if($row["UF_EPG_ID"]!=$arCode["UF_EPG_ID"])
                {
                    $code.= "-".$row["ID"];
                }else{
                    $code = $arCode["UF_CODE"];
                }
            }
            
            \Hawkart\Megatv\ProgTable::update($row["ID"], array(
                "UF_CODE" => $code
            ));
            
            $row["UF_CODE"] = $code;
            $codes[$row["UF_TITLE"]] = $row;
        }      
    }

    public function down()
    {
        global $APPLICATION;
    }

}
