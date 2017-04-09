<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CImage
{
    protected static $cut_dir = "/upload/epg_cut_new/";
    protected static $channel_dir = "/upload/epg_channel/";
    protected static $temp_dir = "/upload/tmp/";
    
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
            "detail" =>  array(
                "width" => 600,
                "height" => 600
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
    public static function copyToServer($img_url, $temp = false)
    {
        $path = self::getName($img_url, $temp);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $path, file_get_contents($img_url));
        
        return $path;
    }
    
    public static function getName($img_url, $temp=false)
    {
        $path_parts = pathinfo($img_url);
        $file_name = $path_parts["filename"];
        $dir = (!$temp) ? self::$cut_dir : self::$temp_dir;
        $path = $dir . $file_name . ".jpg";
        
        return $path;
    }
    
    public static function crop($prog_id, $img_url)
    {
        $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
        $arTemplates = self::getTemplates();
        
        $imgs = array();
        
        //copy image to server
        $img_path = self::copyToServer($img_url, true);
        
        //Get faces cords
        $arCords = FaceDetect::getCord($DOCUMENT_ROOT.$img_path);
        $arSize = FaceDetect::getSizeByFaceCords($arCords);
        //$img_output = FaceDetect::check($img_path);
        
        //Wi, Hi — размеры оригинала
        list($Wi, $Hi, $type, $attr) = getimagesize($DOCUMENT_ROOT.$img_path);
        
        //crop face
        foreach($arTemplates as $class => $arTemplate)
        {            
            //Wr и Hr — ширина и высота будущей картинки
            $Wr = $arTemplate["width"];
            $Hr = $arTemplate["height"];
            
            $img_path_to = str_replace(array(".jpg", self::$temp_dir), array("", self::$cut_dir), $img_path)."_".$Wr."_".$Hr.".jpg";
            
            if($arTemplate["width"]>$Wi || $arTemplate["height"]>$Hi)
            {
                $image = new \Eventviva\ImageResize($DOCUMENT_ROOT.$img_path);
                $image->crop($Wr, $Hr);
                $image->save($DOCUMENT_ROOT.$img_path_to);
                continue;
            }
                
            if(!file_exists($DOCUMENT_ROOT.$img_path_to))
            {
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
            }
            
            $imgs[$class][] = $img_path_to;
            
            unset($image);
        }
        
        unlink($DOCUMENT_ROOT.$img_path);

        return $imgs;
    }
    
    public static function getBiggestByEpgIcons($arIcons)
    {
        $url = false;
        $max_width = false;
        foreach($arIcons as $arIcon)
        {
            $width = intval($arIcon["@attributes"]["width"]);
            if(!$max_width || $width>$max_width)
            {
                $max_width = $width;
                $url = $arIcon["@attributes"]["src"];
            }
        }
        
        return $url;
    }
    
    public static function cropForProgList($arProgList)
    {
        foreach($arProgList as $prog_id => $image_path)
        {
            if(!empty($image_path))
            {
                $arImages = self::crop($prog_id, $image_path);
                if(count($arImages)>0)
                    ProgTable::saveImageList($prog_id, $arImages);
            }
        }
    }
    
        
    public static function transferImages()
    {
        $result = ProgTable::getList(array(
            'filter' => array("!UF_IMG_LIST" => false),
            'select' => array("ID", "UF_IMG_LIST")
        ));
        while ($arProg = $result->fetch())
        {
            $arImages = $arProg["UF_IMG_LIST"];
            foreach($arImages as &$arImage)
            {
                foreach($arImage as &$img)
                {
                    $img = str_replace(self::$temp_dir, self::$cut_dir, $img);
                }
            }
            
            //print_r($arImages); die();
            ProgTable::saveImageList($arProg["ID"], $arImages);
        }
    }
    
    public static function getImageByClass($arImages, $class)
    {
        $images = $arImages[$class];
        if(empty($images[array_rand($images)]))
        {
            $image = $arImages["double"][0];
        }else{
            $image = $images[array_rand($images)];
        }
        return $image;
    }
    
    public static function getDir()
    {
        return self::$cut_dir;
    }
    
    public static function getTempDir()
    {
        return self::$temp_dir;
    }
    
    public static function getChannelDir()
    {
        return self::$channel_dir;
    }
}