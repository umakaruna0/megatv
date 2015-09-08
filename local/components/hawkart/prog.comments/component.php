<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule('highloadblock');
CModule::IncludeModule('iblock');
$arComments = array();
$userIds = array(); 
$arResult["USERS"] = array();

$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(6)->fetch();
$entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
$entity_data_class = $entity->getDataClass();

$arFilter = array("UF_PROG_ID" => $arParams["PROG_ID"]);
$arOrder = array("UF_DATETIME" => "DESC");
$arSelect = array("UF_USER_ID", "UF_TEXT", "UF_DATETIME");

$rsData = $entity_data_class::getList(array(
	'filter' => $arFilter,
	'select' => $arSelect,
	'limit' => false,
	'order' => $arOrder,
));
while($arTmp = $rsData->Fetch()) 
{
    $arComments[] = $arTmp;
    $userIds[] = $arTmp["UF_USER_ID"];
}

if(count($userIds)>0)
{
    $dbUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("ID" =>$ids));
    while($arUser = $dbUsers->Fetch())
    {
        $arResult["USERS"][$arUser["ID"]] = $arUser;
    }
}

$arResult["COMMENTS"] = $arComments;

$this->IncludeComponentTemplate();
?>