<?
//Устанавливаем сообщения об ошибках
header('content-type: image/jpeg');
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
//проверяем, установлено ли исходное изображение 
if(isset($_GET['path']))
{
    $quality = 100;
    if(isset($_GET["quality"]))
        $quality = intval($_GET["quality"]);
    
    $img_size = GetImageSize($_GET['path']);
    $width = $img_size[0];
    $height = $img_size[1];
    
    if($_GET["grey"]!="false")
    {
        $img = imageCreate($width, $height);
        $img2 = ImageCreateFromJpeg($_GET['path']);
        ImageCopyMerge($img,$img2,0,0,0,0, $width, $height, 100);
        for ($c = 0; $c < 256; $c++) 
        {
            ImageColorAllocate($img, $c,$c,$c);
        }
    }else{
        $img = ImageCreateFromJpeg($_GET['path']);
    }
    
    imagejpeg($img, NULL, $quality);
    imagedestroy($img);
}
?>