<?
//Устанавливаем сообщения об ошибках
header('content-type: image/jpeg');
ini_set("display_errors", "1");
error_reporting(E_ALL);

//проверяем, установлено ли исходное изображение 
if(isset($_GET['path']))
{
    $img_size = GetImageSize($_GET['path']);
    $width = $img_size[0];
    $height = $img_size[1];
    $img = imageCreate($width,$height);
    for ($c = 0; $c < 256; $c++) 
    {
        ImageColorAllocate($img, $c,$c,$c);
    }
    $img2 = ImageCreateFromJpeg($_GET['path']);
    ImageCopyMerge($img,$img2,0,0,0,0, $width, $height, 100);
    imagejpeg($img);
    imagedestroy($img);
}
?>