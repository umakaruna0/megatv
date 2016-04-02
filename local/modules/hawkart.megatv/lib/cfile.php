<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CFile
{
    protected static $origin_dir = "/upload/epg_original/";
    protected static $cut_dir = "/upload/epg_cut/";
    
    public static function getPath($id, $arDimenssion = false)
    {   
        
    }
    
    /**
     * Adding file to local directory
     * 
     * @array $arFields('path_from', 'path_to', 'width', 'height')
     */
    public static function add($arFields)
    {        
        if(file_exists($arFields["path_from"]))
        {
            $image = new \Eventviva\ImageResize($arFields["path_from"]);
        
            list($width, $height, $type, $attr) = getimagesize($arFields["path_from"]);
            
            if( $arFields["width"] && $arFields["height"] && ($arFields["width"]!=$width || $arFields["height"]!=$height) )
            {
                $image->crop($arFields["width"], $arFields["height"]);
            }
            
            if(!$arFields["path_to"])
            {
                $path_parts = pathinfo($arFields["path_from"]);
                $file_name = $path_parts["filename"];
                $image->save($_SERVER["DOCUMENT_ROOT"]. "/upload/epg/". $file_name. ".jpg");
            }else{
                $image->save($arFields["path_to"]);
            }
        }
    }
    
    public static function resize()
    {
        
    }
}