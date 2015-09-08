<?
//Константы
define("FULL_PATH_DOCUMENT_ROOT", "/home/d/daotel/MEGATV/public_html"); //изменить на сервере
define("LOG_FILENAME", "/logs/import.txt");
define("CHANNEL_IB", 6);    //ид каналов
define("PROG_IB", 7);   //ид программ
define("PROG_TIME_IB", 8);   //ид показа программ
define("CITY_IB", 5);
define("SERVICE_IB", 13);
define("BROADCAT_COLS", 24);
define("USER_SOCIAL_IB", 9);
define("PASSPORT_IB", 10);
define("SOCIAL_CONFIG_IB", 11);
define("SUBSCRIBE_HL", 4);
define("RECORD_HL", 5);

use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Type\DateTime;

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
        '\CSaleAccountEx' => $sClassesPath.'CSaleAccountEx.php',
        '\CChannel' => $sClassesPath.'CChannel.php',
        '\CProg' => $sClassesPath.'CProg.php',
        '\CProgTime' => $sClassesPath.'CProgTime.php',
        '\CEpg' => $sClassesPath.'CEpg.php',
        '\CTimeEx' => $sClassesPath.'CTimeEx.php',
        '\CScheduleTable' => $sClassesPath.'CScheduleTable.php',
        '\CSocialAuth' => $sClassesPath.'CSocialAuth.php',
        '\CSubscribeEx' => $sClassesPath.'CSubscribeEx.php',
        '\CSotal' => $sClassesPath.'CSotal.php',
        '\UploadHandler' => $sClassesPath.'UploadHandler.php',
        '\CRecordEx' => $sClassesPath.'CRecordEx.php',
        '\CServiceEx' => $sClassesPath.'CServiceEx.php',
        '\CUserEx' => $sClassesPath.'CUserEx.php',
        '\CCommentEx' => $sClassesPath.'CCommentEx.php',
	)
);

AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
AddEventHandler("main", "OnBeforeUserLogin", Array("CUserEx", "OnBeforeUserLogin"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserRegister"));
AddEventHandler("main", "OnAfterUserAdd", Array("CUserEx", "OnAfterUserUpdateHandler"));

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