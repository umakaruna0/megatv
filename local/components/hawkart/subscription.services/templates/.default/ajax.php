<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$arResult = array();
$result = array();
$error = false;
$ServiceID = intval($_REQUEST["serviceID"]);
$status = htmlspecialcharsbx($_REQUEST["status"]);
$selectedServices = array();

//Получим инфу о сервисе
$result = \Hawkart\Megatv\ServiceTable::getById($ServiceID);
$arService = $result->fetch();

//get subcribe channel list
$result = \Hawkart\Megatv\SubscribeTable::getList(array(
    'filter' => array("=UF_USER_ID" => $USER->GetID(), ">UF_SERVICE_ID" => 0),
    'select' => array("UF_SERVICE_ID", "ID")
));
while ($arSub = $result->fetch())
{
    $selectedServices[$arSub["UF_SERVICE_ID"]] = $arSub["ID"];
}

//Если гугл или яндекс-диск и включен, то ничего не делаем
if(isset($selectedServices[$ServiceID]) && $arService["UF_DISK_TYPE"])
{
    exit(json_encode(array("status"=>"enable")));
}

$CSubscribe = new \Hawkart\Megatv\CSubscribe("SERVICE");
//Если нет подписки или есть и + 5 или +10 ГБ
if(!isset($selectedServices[$ServiceID]) || !$arService["UF_DISK_TYPE"])
{
    $result = $CSubscribe->setUserSubscribe($ServiceID);
}else{
    if($status=="enable")
    {
        $active = 1;
    }else{
        $active = 0;
    }
    
    //Если стоит больше 0 руб, то не выключаем подписку
    if(intval($arService["UF_PRICE"])>0)
    {
        $status=="enable";
    }else{
        $subscribeID = $selectedServices[$ServiceID];
        $result = $CSubscribe->updateUserSubscribe($subscribeID, array("UF_ACTIVE"=>$active));
    }
}

//Если не вышло добавить/обновить подписку
if(!$result)
{
    $error = "Ошибка";
    if($status=="enable")
    {
        $status = "disable";
    }else{
        $status = "enable";
    }
}

$arResult = array("status"=>$status, "error"=>$error);

//если + 5 или +10 ГБ
if($result && empty($arService["UF_DISK_TYPE"]))
{
    $arResult["status"] = "temporal";
    $rsUser = \CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $arResult["updatedSpace"] = intval($arUser["UF_CAPACITY"]);
}
 
exit(json_encode($arResult));
?>