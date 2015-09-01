<?
$_SERVER["DOCUMENT_ROOT"] = "/home/d/daotel/MEGATV/public_html"; //изменить на сервере
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

//Получим список записей для каждого пользователей, у которых нет еще ссылки на видео
$arUserRecords = array();
$dt = new Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', time()), 'Y-m-d H:i:s');
$arFilter = array(
    "UF_URL" => false,
    "<=UF_DATE_END" => $dt
);
$arRecordsWait = CRecordEx::getList($arFilter, array("UF_USER", "UF_SOTAL_ID"));
foreach($arRecordsWait as $arRecord)
{
    $arUserRecords[$arRecord["UF_USER"]][] = $arRecord["UF_SOTAL_ID"];
}

$filter = Array("ACTIVE" =>"Y", "!UF_SOTAL_LOGIN" => false);
$rsUsers = CUser::GetList(($by="LAST_NAME"), ($order="asc"), $filter);
while($arUser = $rsUsers->GetNext())
{
    //Если у пользователя есть программы, ожидающие записи
    if(isset($arUserRecords[$arUser["ID"]]) && !empty($arUserRecords[$arUser["ID"]]))
    {
        $ids = $arUserRecords[$arUser["ID"]];
        
        $Sotal = new CSotal($arUser["ID"]);
        $Sotal->getSubscriberToken();
        $arSchedules = $Sotal->getScheduleList();
        
        foreach($arSchedules["schedule"] as $arSchedule)
        {
            $record_id = $arSchedule["id"];
            $status = $arSchedule["state"];
            
            if(intval($status)==3 && in_array($record_id, $ids))
            {
                $arStream = $Sotal->getStreamUrl($record_id);
                $url = $arStream["url"];
                
                if(!empty($url))
                {
                    $user_record = CRecordEx::getBySotalID($record_id);
                    CRecordEx::update($user_record["ID"], array("UF_URL" => $url));
                }        
            }
        }
        
        unset($Sotal);
    }
}

echo "loaded";

die();