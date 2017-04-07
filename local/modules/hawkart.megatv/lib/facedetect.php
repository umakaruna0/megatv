<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class FaceDetect
{
    public static function checkExist($img)
    {
        exec('/usr/bin/facedetect -q '.$img);
        $result = exec('echo $?');
        
        if($result==0)
        {
            return true;
        }else{
            return false;
        } 
    }  
    
    public static function getCord($img)
    {
        ob_start();
        passthru('/usr/bin/facedetect '.$img);
        $data = ob_get_clean();
        $arCords = explode("\n", $data);
        
        $cords = array();
        foreach($arCords as $key=>$arCord)
        {
            if(!empty($arCord))
            {
                $cords[] = explode(" ", $arCord);
            }
        }
        return $cords;
    }
    
    public static function check($img)
    {
        $path_parts = pathinfo($img);
        $file_name = $path_parts["filename"];
        $img_output = str_replace($file_name, $file_name."_output", $img);
        exec('/usr/bin/facedetect -o '.$_SERVER["DOCUMENT_ROOT"] . $img_output. " ".$_SERVER["DOCUMENT_ROOT"].$img);

        return $img_output;
    }  
    
    /**
     * Get size and cords for croping the image
     */
    public static function getSizeByFaceCords($arCords)
    {
        $xMin = false;
        $yMin = false;
        $xMax = false;
        $yMax = false;
        foreach($arCords as $arCord)
        {
            if(!$xMin || $xMin && $arCord[0]<$xMin)
            {
                $xMin = $arCord[0];
            }
            
            if(!$yMin || $yMin && $arCord[1]<$yMin)
            {
                $yMin = $arCord[1];
            }
            
            $xRight = $arCord[0]+$arCord[2];
            if(!$xMax || $xMax && $xRight>$xMax)
            {
                $xMax = $xRight;
            }
            
            $yRight = $arCord[1]+$arCord[3];
            if(!$yMax || $yMax && $yRight>$yMax)
            {
                $yMax = $yRight;
            }
        }
        
        $width = $xMax-$xMin;
        $height = $yMax-$yMin;
        
        return [
            "x" => $xMin, 
            "y" => $yMin, 
            "width" => $width, 
            "height" => $height, 
            "xmax" => $xMax, 
            "ymax" => $yMax 
        ];
    }
}