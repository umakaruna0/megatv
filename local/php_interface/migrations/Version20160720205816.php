<?php

namespace Sprint\Migration;

class Version20160720205816 extends Version {

    protected $description = "Добавление даты окончания подписки для платных каналов.";

    public function up()
    {
        $arHlData = array(
            'FIELDS' => array(
                'UF_DATETIME_TO' => array('N', 'datetime', array(
                    'EDIT_FORM_LABEL' => array(
                        'ru' => 'Дата окончания подписки',
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru' => 'Дата окончания подписки',
                    ),
                )),
            )
        );
        
        global $APPLICATION, $DB;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\SubscribeTable::getTableName(),
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
                        $this->out('Обновлено свойство - '.$fieldValue[2]['EDIT_FORM_LABEL']['ru']);
                        
                    } else {
                        if (($ex = $APPLICATION->GetException())) 
                        {
                            $this->outError('Ошибка - '.$ex->GetString());
                        }
                    }
                } else {
                    if ($idUserTypeProp = $oUserTypeEntity->Add($aUserField)) 
                    {
                        $this->out('Добавлено новое свойство - '.$fieldValue[2]['EDIT_FORM_LABEL']['ru']);
                        
                    } else {
                        if (($ex = $APPLICATION->GetException())) 
                        {
                            $this->outError('Ошибка - '.$ex->GetString());
                        }
                    }
                }
            
                $sort += 100;
            }
            
            $strSql = "UPDATE ".\Hawkart\Megatv\SubscribeTable::getTableName()." SET UF_DATETIME_TO=curdate() + INTERVAL 1 DAY + INTERVAL 3 HOUR + INTERVAL 30 MINUTE";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
            
            $this->outSuccess('Все готово на %d%%', 100);
        }
    }

    public function down()
    {
        global $APPLICATION;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\SubscribeTable::getTableName(),
            ))
        )->fetch();
        
        if($arHlblock)
        {
            $arProps = array("UF_DATETIME_TO");
            
            foreach($arProps as $prop_name)
            {
                $oUserTypeEntity = new \CUserTypeEntity();
                $resProperty = \CUserTypeEntity::GetList(
                    array(),
                    array('ENTITY_ID' => 'HLBLOCK_' . $arHlblock["ID"], 'FIELD_NAME' => $prop_name)
                );
                if ($aUserHasField = $resProperty->Fetch()) 
                {
                    $oUserTypeEntity->Delete($aUserHasField['ID']);
                    $this->outSuccess("Свойство 'Дата окончания подписки' удалено!");
                }
            }
            
        }
    }

}
