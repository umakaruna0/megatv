<?php

namespace Sprint\Migration;
use \Sprint\Migration\Helpers\IblockHelper;

class Version20160712133400 extends Version {

    protected $description = "Добавление свойства 'Дата добавления' в Список Внешних программ для сортировки";

    public function up()
    {
        $arHlData = array(
            'FIELDS' => array(
                'UF_DATETIME' => array('N', 'datetime', array(
                    'EDIT_FORM_LABEL' => array(
                        'ru' => 'Дата добавления',
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru' => 'Дата добавления',
                    ),
                )),
            )
        );
        
        global $APPLICATION;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\ProgExternalTable::getTableName(),
            ))
        )->fetch();
        
        if($arHlblock)
        {
            $oUserTypeEntity = new \CUserTypeEntity();
            $sort = 500;
            
            foreach ($arHlData['FIELDS'] as $fieldName => $fieldValue) 
            {
                $aUserField = array(
                    'ENTITY_ID' => 'HLBLOCK_' . $arHlblock["ID"],
                    'FIELD_NAME' => $fieldName,
                    'USER_TYPE_ID' => $fieldValue[1],
                    'SORT' => $sort,
                    'MULTIPLE' => 'N',
                    'MANDATORY' => $fieldValue[0],
                    'SHOW_FILTER' => 'N',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'IS_SEARCHABLE' => 'N',
                    'SETTINGS' => array(),
                );
            
                if (isset($fieldValue[2]) && is_array($fieldValue[2])) 
                {
                    $aUserField = array_merge($aUserField, $fieldValue[2]);
                }
            
                $resProperty = \CUserTypeEntity::GetList(
                    array(),
                    array('ENTITY_ID' => $aUserField['ENTITY_ID'], 'FIELD_NAME' => $aUserField['FIELD_NAME'])
                );
                if ($aUserHasField = $resProperty->Fetch()) 
                {
                    $idUserTypeProp = $aUserHasField['ID'];
                    if ($oUserTypeEntity->Update($idUserTypeProp, $aUserField)) 
                    {
                        $this->out('Обновлено свойство - '.$aUserField['FIELD_NAME']);
                        
                    } else {
                        if (($ex = $APPLICATION->GetException())) 
                        {
                            $this->outError('Ошибка - '.$ex->GetString());
                        }
                    }
                } else {
                    if ($idUserTypeProp = $oUserTypeEntity->Add($aUserField)) 
                    {
                        $this->out('Добавлено новое свойство - '.$aUserField['FIELD_NAME']);
                        
                    } else {
                        if (($ex = $APPLICATION->GetException())) 
                        {
                            $this->outError('Ошибка - '.$ex->GetString());
                        }
                    }
                }
            
                $sort += 100;
            }
            
            //Import datetime to field from Json field
            $result = \Hawkart\Megatv\ProgExternalTable::getList(array(
                'filter' => array(),
                'select' => array("ID", "UF_JSON")
            ));
            while ($row = $result->fetch())
            {
                $datetime = $row["UF_JSON"]["created_ts"];
                if(!empty($datetime))
                {
                    $datetime = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($datetime)), 'Y-m-d H:i:s' );
                    \Hawkart\Megatv\ProgExternalTable::update($row["ID"], array(
                        "UF_DATETIME" => $datetime
                    ));
                }
            }
            
            $this->outSuccess('Все готово на %d%%', 100);
        }

    }

    public function down()
    {
        global $APPLICATION;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\ProgExternalTable::getTableName(),
            ))
        )->fetch();
        
        if($arHlblock)
        {
            $oUserTypeEntity = new \CUserTypeEntity();
            $resProperty = \CUserTypeEntity::GetList(
                array(),
                array('ENTITY_ID' => 'HLBLOCK_' . $arHlblock["ID"], 'FIELD_NAME' => "UF_DATETIME")
            );
            if ($aUserHasField = $resProperty->Fetch()) 
            {
                $oUserTypeEntity->Delete($aUserHasField['ID']);
                $this->outSuccess("Свойство 'Дата добавления' удалено!");
            }
        }
    }
}