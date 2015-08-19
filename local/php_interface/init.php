<?
//Константы
define("FULL_PATH_DOCUMENT_ROOT", "/home/d/daotel/MEGATV/public_html"); //изменить на сервере
define("LOG_FILENAME", "/logs/import.txt");
define("CHANNEL_IB", 6);    //ид каналов
define("PROG_IB", 7);   //ид программ
define("PROG_TIME_IB", 8);   //ид показа программ
define("CITY_IB", 5);
define("BROADCAT_COLS", 24);
define("USER_SOCIAL_IB", 9);

\Bitrix\Main\Loader::includeModule('olegpro.ipgeobase');

// Классы
$sClassesPath = '/local/php_interface/classes/';
CModule::AddAutoloadClasses(
	'',
	array(
        '\CCacheEx' => $sClassesPath.'CCacheEx.php',
        '\CXmlEx' => $sClassesPath.'CXmlEx.php',
		'\CDev' => $sClassesPath.'CDev.php',
        '\CCityEx' => $sClassesPath.'CCity.php',
        '\CChannel' => $sClassesPath.'CChannel.php',
        '\CProg' => $sClassesPath.'CProg.php',
        '\CProgTime' => $sClassesPath.'CProgTime.php',
        '\CEpg' => $sClassesPath.'CEpg.php',
        '\CTimeEx' => $sClassesPath.'CTimeEx.php',
        '\CScheduleTable' => $sClassesPath.'CScheduleTable.php',
        '\CSocialAuth' => $sClassesPath.'CSocialAuth.php',
	)
);

AddEventHandler("main", 'OnProlog', 'setCurrentSectioCodeBySectionCodePath');
AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
AddEventHandler("main", "OnBeforeUserLogin", Array("CUserEx", "OnBeforeUserLogin"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserRegister"));
//AddEventHandler("main", "OnAfterUserAdd", Array("CUserEx", "OnAfterUserUpdateHandler"));
//AddEventHandler("main", "OnAfterUserUpdate", Array("CUserEx", "OnAfterUserUpdateHandler"));

// обработка опенграфовских мета-тегов
AddEventHandler('main', 'OnEpilog', array('CMyEpilogHooks', 'OpenGraph'));
class CMyEpilogHooks
{
    function OpenGraph()
    {
        GLOBAL $APPLICATION;
        foreach (array('og_title', 'og_image', 'og_url', 'og_site_name', 'og_type', 'og_description') as $prop_name)
        {
            $value = $APPLICATION->GetDirProperty($prop_name);
            
            if ($prop_name == 'og_url' && empty($value))
                $value = $APPLICATION->GetCurPage(false);
            
            if (in_array($prop_name, array('og_image', 'og_url')) && !empty($value))
                $value = "http://".$_SERVER["SERVER_NAME"].$value;
            
            $prop_code = str_replace('og_', 'og:', $prop_name);
            if (!empty($value))
                $APPLICATION->AddHeadString("<meta property=\"$prop_code\" content=\"$value\" />");
        }
    }
}

function _Check404Error()
{
   if (defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION'))
   {
       GLOBAL $APPLICATION;
       $APPLICATION->RestartBuffer();
       include $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.SITE_TEMPLATE_ID.'/header.php';
       require ($_SERVER['DOCUMENT_ROOT'].'/404.php');
       include $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.SITE_TEMPLATE_ID.'/footer.php';
   }
}

class CUserEx
{
    function OnBeforeUserLogin($arFields)
    {
        $filter = Array("=EMAIL" =>$arFields["LOGIN"]);
        $rsUsers = CUser::GetList(($by="LAST_NAME"), ($order="asc"), $filter);
        if($user = $rsUsers->GetNext())
            $arFields["LOGIN"] = $user["LOGIN"];
    }
    
    function OnBeforeUserRegister($arFields)
    {
        $arFields["LOGIN"] = $arFields["EMAIL"];
        $arFields["PERSONAL_BIRTHDAY"] = $arFields["USER_PERSONAL_BIRTHDAY"];
    }
    
    /**
     * При загрузке аватара уменьшаем его размер до 150х150px
     */
    public static function OnAfterUserUpdateHandler($USER_ID/*&$arFields*/)
    {
        $imageMaxWidth = 216; // Максимальная ширина уменьшенной картинки 
        $imageMaxHeight = 216; // Максимальная высота уменьшенной картинки
        
        $rsUser = CUser::GetByID($USER_ID/*$arFields["ID"]*/);
        $arUser = $rsUser->Fetch();
        
        if(intval($arUser["PERSONAL_PHOTO"])>0)
        {
            $arFile = CFile::GetFileArray($arUser["PERSONAL_PHOTO"]);
            
            // проверяем, что файл является картинкой
            if (!CFile::IsImage($arFile["FILE_NAME"]))
            {
                echo "не является картинкой";
                continue;
            }
                
            // Если размер больше допустимого
            if ($arFile["WIDTH"] > $imageMaxWidth || $arFile["HEIGHT"] > $imageMaxHeight)
            {
                // Временная картинка
                $tmpFilePath = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/".$arFile["FILE_NAME"];
                
                // Уменьшаем картинку
                $resizeRez = CFile::ResizeImageFile( // уменьшение картинки для превью
                    $source = $_SERVER['DOCUMENT_ROOT'].$arFile["SRC"],
                    $dest = $tmpFilePath,
                    array(
                        'width' => $imageMaxWidth,
                        'height' => $imageMaxHeight
                    ),
                    $resizeType = BX_RESIZE_IMAGE_EXACT,//BX_RESIZE_IMAGE_PROPORTIONAL, // метод ресайза
                    $waterMark = array(), // водяной знак (пустой)
                    $jpgQuality = 95 // качество уменьшенной картинки в процентах
                );
                
                // Записываем изменение в свойство
                if ($resizeRez && $tmpFilePath) 
                {
                    $arNewFile = CFile::MakeFileArray($tmpFilePath);

                    $arNewFile['del'] = "Y";
                    $arNewFile['old_file'] = $arUser['PERSONAL_PHOTO'];
                    $arNewFile["MODULE_ID"] = "main";
                    $fields['PERSONAL_PHOTO'] = $arNewFile;
                    
                    $user = new CUser;
                    $user->Update($arUser["ID"], $fields);
                    
                    $arUser["PERSONAL_PHOTO"] = $arNewFile;
                    
                    // Удалим временный файл
                    unlink($tmpFilePath);
                } 
            }
        }
        
        return $arUser;
    }
}