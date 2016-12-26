<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;
use Snoopy\Snoopy;

Localization\Loc::loadMessages(__FILE__);

class Tvzavr{
    
    public static function getList()
    {
        /*$path = $_SERVER["DOCUMENT_ROOT"]."/upload/tvzavr.json";
        $str = file_get_contents($path);
        $array = json_decode($str, true);*/
        
        return array(
            array(
            "UF_TITLE" => "Рассказы",
            "DETAIL_PAGE_URL" => "https://www.tvzavr.ru/films/Rasskazy",
            "PICTURE" => "https://www.tvzavr.ru/common/tvzstatic/cache/252x140/22654.jpg"
        ));
    }
    
    public static function getRand($key=false)
    {
        $array = self::getList();
        if($key && array_key_exists($key, $array))
        {
            
        }else{
            $key = array_rand($array);
        }
        
        return $array[$key];
    }
}