<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new \CUser;
    
//server side php
$action = htmlspecialchars($_POST["action"]);
$dir = \Hawkart\Megatv\CImage::getDir();

switch($action)
{
    case "uploadByUrl":
        
        $img_url = htmlspecialchars($_POST["url"]);
        $path_parts = pathinfo($img_url);
        $file_name = $path_parts["filename"];
        $path = "/test/crop/temp/" . $file_name . ".jpg";
        
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $path, file_get_contents($img_url));
        
        echo $path;
    break;
    
    case "saveCrop":
        $prog_id = intval($_POST["prog_id"]);
        $class= htmlspecialchars($_POST["pkey"]);
        $img = $_POST['pngimageData'];
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        
        $img_to = $dir.$prog_id."_".time().".jpg";
        
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$img_to, $data);
        
        //resize to right size
        $image = new \Eventviva\ImageResize($_SERVER["DOCUMENT_ROOT"].$img_to);
        $image->resize(intval($_POST["width"]), intval($_POST["height"]));
        $image->save($_SERVER["DOCUMENT_ROOT"].$img_to);
        
        \Hawkart\Megatv\ProgTable::addToImageList($prog_id, $img_to, $class);
        
        echo "added";
    break;
    
    case "searchProg":
        $query = htmlspecialchars($_POST["q"]);
        $arProgs = array();
        
        if (preg_match('/^\+?\d+$/', $query)) 
        {
            $arFilter = array(
                '=ID' => intval($query)
            );
        }else{
            $arFilter = array(
                '%UF_TITLE' => strtolower($query)
            );
        }
        $arSelect = array("ID", "UF_TITLE");
        $result = \Hawkart\Megatv\ProgTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 20
        ));
        while($arProg = $result->fetch())
        {
            $arProgs[] = $arProg;
        }
        echo json_encode($arProgs);
    break;
    
    case "getImagesByProgId":
        $prog_id = intval($_POST["prog_id"]);
        $arImages = \Hawkart\Megatv\ProgTable::getImages($prog_id);
        
        $images = array();
        foreach($arImages as $arList)
        {
            if(count($arList)>0)
            {
                foreach($arList as $image)
                {
                    $images[] = $image;
                }
            }
        }
        
        unset($arImages);
        
        echo json_encode(array("images" => $images));
    break;
    
    case "deleteImage":
        $prog_id = intval($_POST["prog_id"]);
        $img_path = $_POST["image"];
        
        $arImages = \Hawkart\Megatv\ProgTable::getImages($prog_id);
        
        $images = array();
        foreach($arImages as $key=>$arList)
        {
            if(count($arList)>0)
            {
                foreach($arList as $image)
                {
                    if($image==$img_path) continue;
                    $images[$key][] = $image;
                }
            }
        }
        
        unset($arImages);
        
        \Hawkart\Megatv\ProgTable::update($prog_id, array(
            "UF_IMG_LIST" => $images
        ));
        
        unlink($_SERVER["DOCUMENT_ROOT"].$img_path);
        
        echo json_encode(array());
    break;
}