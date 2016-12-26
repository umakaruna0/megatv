<?php

namespace Sprint\Migration;

class Version20160818120449 extends Version {

    protected $description = "Дата добавления для записей";
    
    protected $arHlData = array(
        'FIELDS' => array(
            'UF_DATETIME_ADD' => array('N', 'datetime', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Дата добавления',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Дата добавления',
                ),
            )),
            'UF_EPG_ID' => array('N', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Epg id',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Epg id',
                ),
            )),
            'UF_DELETED' => array('N', 'boolean', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Удалено',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Удалено',
                ),
            )),
        )
    );
    
    public function up()
    {
        global $APPLICATION;
        $arHlData = $this->arHlData;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\RecordTable::getTableName(),
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
            
            $arFilter = array();            
            $arSelect = array(
                "ID"
            );
            $result = \Hawkart\Megatv\RecordTable::getList(array(
                'filter' => $arFilter,
                'select' => $arSelect,
            ));
            while ($arRecord = $result->fetch())
            {
                \Hawkart\Megatv\RecordTable::update($arRecord["ID"], array(
                    'UF_DATETIME_ADD' => new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s')
                ));
            }
            
            $oUserTypeEntity = new \CUserTypeEntity();
            $resProperty = \CUserTypeEntity::GetList(
                array(),
                array('ENTITY_ID' => 'HLBLOCK_' . $arHlblock["ID"], 'FIELD_NAME' => "UF_SOTAL_ID")
            );
            if ($aUserHasField = $resProperty->Fetch()) 
            {
                $oUserTypeEntity->Delete($aUserHasField['ID']);
                $this->outSuccess("Свойство удалено!");
            }
            
            $this->outSuccess('Все готово на %d%%', 100);
        }

    }

    public function down()
    {
        global $APPLICATION;
        $arHlData = $this->arHlData;
        \Bitrix\Main\Loader::includeModule("highloadblock");
        $arHlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'filter' => array(
                'TABLE_NAME' => \Hawkart\Megatv\RecordTable::getTableName(),
            ))
        )->fetch();
        
        if($arHlblock)
        {
            foreach($arHlData as $fieldName => $fieldValue)
            {
                $oUserTypeEntity = new \CUserTypeEntity();
                $resProperty = \CUserTypeEntity::GetList(
                    array(),
                    array('ENTITY_ID' => 'HLBLOCK_' . $arHlblock["ID"], 'FIELD_NAME' => $fieldName)
                );
                if ($aUserHasField = $resProperty->Fetch()) 
                {
                    $oUserTypeEntity->Delete($aUserHasField['ID']);
                    $this->outSuccess("Свойство удалено!");
                }
            }
            
        }
    }

}