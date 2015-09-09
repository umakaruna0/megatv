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
$arService = CServiceEx::getByID($ServiceID, array("PROPERTY_DISK_VALUE", "PROPERTY_PRICE"));

$CSubscribeEx = new CSubscribeEx("SERVICE");
$arServices = $CSubscribeEx->getList(array("UF_USER"=>$USER->GetID()), array("UF_SERVICE", "ID"));
foreach($arServices as $arService)
{
    $selectedServices[$arService["UF_SERVICE"]] = $arService["ID"];
}

//Если гугл или яндекс-диск и включен, то ничего не делаем
if(isset($selectedServices[$ServiceID]) && $arService["PROPERTY_DISK_VALUE"])
{
    exit(json_encode(array("status"=>"enable")));
}

//Если нет подписки или есть и + 5 или +10 ГБ
if(!isset($selectedServices[$ServiceID]) || !$arService["PROPERTY_DISK_VALUE"])
{
    $result = $CSubscribeEx->setUserSubscribe($ServiceID);
}else{
    if($status=="enable")
    {
        $active = "Y";
    }else{
        $active = "N";
    }
    
    //Если стоит больше 0 руб, то не выключаем подписку
    if(intval($arService["PROPERTY_PRICE_VALUE"])>0)
    {
        $status=="enable";
    }else{
        $subscribeID = $selectedServices[$ServiceID];
        $result = $CSubscribeEx->updateUserSubscribe($subscribeID, array("UF_ACTIVE"=>$active));
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
if($result && !$arService["PROPERTY_DISK_VALUE"])
{
    $arResult["status"] = "temporal";
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $arResult["updatedSpace"] = intval($arUser["UF_CAPACITY"]);
}
 
exit(json_encode($arResult));
?>