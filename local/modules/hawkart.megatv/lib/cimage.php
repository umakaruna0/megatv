<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CImage
{
    protected static $cut_dir = "/upload/epg_cut_new/";
    protected static $epg_dir = "/upload/epg_original/";
    
    public static function getTemplates()
    {
        $arTemplates = array(
            "one" => array(
                "width" => 288,
                "height" => 288
            ),
            "half" => array(
                "width" => 288,
                "height" => 144
            ), 
            "double" => array(
                "width" => 576,
                "height" => 288
            ),
            "3" => array(
                "width" => 864,
                "height" => 288
            ),
            /*"4" => array(
                "width" => 1152,
                "height" => 288
            ),
            "5" => array(
                "width" => 1440,
                "height" => 288
            ),*/
        );
        
        return $arTemplates;
    }
    
    /**
     * Copy original image to server in tmp dir
     * 
     * @return string $path
     */
    public static function copyToServer($img_url)
    {
        $path = self::getName($img_url);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $path, file_get_contents($img_url));
        
        return $path;
    }
    
    public static function getName($img_url)
    {
        $path_parts = pathinfo($img_url);
        $file_name = $path_parts["filename"];
        $path = self::$cut_dir . $file_name . ".jpg";
        
        return $path;
    }
    
    public static function crop($prog_id, $arIcons)
    {
        $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
        $arTemplates = self::getTemplates();
        
        $imgs = array();
        foreach($arIcons as $key=>$arIcon)
        {
            $img_url = $arIcon["@attributes"]["src"];
            
            //copy image to server
            $img_path = self::copyToServer($img_url);
            
            //Get faces cords
            $arCords = FaceDetect::getCord($DOCUMENT_ROOT.$img_path);
            $arSize = FaceDetect::getSizeByFaceCords($arCords);
            $img_output = FaceDetect::check($img_path);
            
            //Wi, Hi — размеры оригинала
            list($Wi, $Hi, $type, $attr) = getimagesize($DOCUMENT_ROOT.$img_path);
            
            $addImages = array();
            
            //crop face
            foreach($arTemplates as $class => $arTemplate)
            {
                if($arTemplate["width"]>$Wi || $arTemplate["height"]>$Hi)
                    continue;
                
                //Wr и Hr — ширина и высота будущей картинки
                $Wr = $arTemplate["width"];
                $Hr = $arTemplate["height"];
                
                $img_path_to = str_replace(".jpg", "", $img_path)."_f".$class.".jpg";
                
                if(count($arCords)>0)
                {
                    //Вычисляем пропорции конечного изображения
                    $k = $Wr/$Hr;
                    
                    //Определяем максимальный прямоугольник, который впишется в оригинальное изображение
                    //Wm, Hm — размеры максимального прямоугольника
                    $Wm = $Wi;
                    $Hm = round($Wi/$k, 0);
                    
                    if($Hm >= $Hi)
                    {
                        $Hm = $Hi;
                        $Wm = round($Hm*$k, 0);
                    }
                    
                    if($class=="half")
                    {
                        $kw = 10;
                    }else{
                        $kw = intval($class);
                    }
                    
                    $koef = 0.5;
                    $koef2 = 0.5 - $kw*0.03;
                    //$koef2 = 0;
                    $fx = intval($arSize["x"]+$arSize["width"]*$koef);
                    $fy = intval($arSize["y"]+$arSize["height"]*$koef2);
                    
                    //Вычисляем новые координаты для точки фокуса
                    $fx2 = round($fx*$Wm/$Wi, 0);
                    $fy2 = round($fy*$Hm/$Hi, 0);
                    
                    $canvas = imagecreatetruecolor($Wm, $Hm);
                    $image = imagecreatefromjpeg($DOCUMENT_ROOT.$img_path);
                    imagecopy($canvas, $image, 0, 0, ($fx-$fx2), ($fy-$fy2), $Wm, $Hm);
                    imagejpeg($canvas, $DOCUMENT_ROOT.$img_path_to, 100);
                    unset($image);
                    unset($canvas);
                    
                    $image = new \Eventviva\ImageResize($DOCUMENT_ROOT.$img_path_to);
                    $image->resize($Wr, $Hr);
                    $image->save($DOCUMENT_ROOT.$img_path_to);
                    
                    exec('convert '.$DOCUMENT_ROOT.$img_path_to.' -unsharp 1.5×1.0+1.5+0.02 '.$DOCUMENT_ROOT.$img_path_to);
                        
                }else{
                    
                    $image = new \Eventviva\ImageResize($DOCUMENT_ROOT.$img_path);
                    $image->crop($Wr, $Hr);
                    $image->save($DOCUMENT_ROOT.$img_path_to);
                }
                
                $imgs[$class][] = $img_path_to;
                
                unset($image);
                $addImages[] = $img_url;
            }
            
            $addImages = array_unique($addImages);
            
            unlink($DOCUMENT_ROOT.$img_path);
        }
        
        return array(
            "croped" => $imgs,
            "urls" => $addImages
        );
    }
    
    public static function saveProgImages($prog_id, $arIcons)
    {
        $arData = self::crop($prog_id, $arIcons);
        
        //get img from db by prog_id
        $urls = array();
        $result = ImageTable::getList(array(
            'filter' => array("=UF_PROG_ID" => $prog_id),
            'select' => array('UF_PATH')
        ));
        while ($row = $result->fetch())
        {
            $urls[] = $row["UF_IMG_PATH"];
        }
        
        foreach($arData["urls"] as $url)
        {
            if(!in_array($url, $urls))
            {
                ImageTable::add(array(
                    "UF_PROG_ID" => $prog_id,
                    "UF_PATH" => $url
                ));
            }
        }
        
        //save to prop json
        ProgTable($prog_id, array(
            "UF_IMG_LIST" => $arData["croped"]
        ));
    }
    
    public static function getDir()
    {
        return self::$cut_dir;
    }
    
    public static function deleteOldCrops()
    {
        
    }
}