<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class SubscribeTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_subscribe';
	}
    
	/**
	 * Returns entity map definition
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true
			),
            'UF_ACTIVE' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1),
				'required'  => true
			),
			'UF_USER_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
            'UF_CHANNEL_ID' => array(
				'data_type' => 'integer',
			),
            'UF_CHANNEL' => array(
				'data_type' => '\Hawkart\Megatv\ChannelBaseTable',
				'reference' => array('=this.UF_CHANNEL_ID' => 'ref.ID'),
			),
            'UF_DATE_FROM' => array(
				'data_type' => 'datetime'
			),
            'UF_SERVICE_ID' => array(
				'data_type' => 'integer',
			),
            'UF_SERVICE' => array(
				'data_type' => '\Hawkart\Megatv\ServiceTable',
				'reference' => array('=this.UF_SERVICE_ID' => 'ref.ID'),
			),
            'UF_DATETIME_TO' => array(
				'data_type' => 'datetime'
			),
		);
	}
    
    /**
     * Clear table
     */
    public static function deleteAll()
    {
        global $DB;
        $DB->Query("DELETE FROM ".self::getTableName(), false);
        $DB->Query("ALTER TABLE ".self::getTableName()." AUTO_INCREMENT=1", false);
    }
    
    public static function deleteDuplicate()
    {
        $selectedChannels = array();
        $res = self::getList(array(
            'filter' => array(">UF_CHANNEL_ID" => 0),
            'select' => array("UF_CHANNEL_ID", "ID", "UF_USER_ID")
        ));
        while ($arSub = $res->fetch())
        {
            $xid = $arSub["UF_CHANNEL_ID"]."-".$arSub["UF_USER_ID"];
            
            if(intval($selectedChannels[$xid])>0)
            {
                self::delete($arSub["ID"]);
                //echo "delete=".$arSub["ID"]."  = ".$selectedChannels[$xid]."<br />";
            }else{
                $selectedChannels[$xid] = $arSub["ID"];
            }
        }
    }
}