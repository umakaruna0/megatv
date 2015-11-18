<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");

$progIds = array();
$arFilter = array(
    "UF_USER" => $USER->GetID()
);

if($arParams["WATCHED"]=="Y")
{
    $arFilter["!UF_WATCHED"] = false;
}

$arRecords = CRecordEx::getList($arFilter, array("ID", "UF_PROG", "UF_URL", "UF_PROGRESS_PERS", "UF_NAME", "UF_SUB_TITLE", "UF_PICTURE"));

/*if(count($arRecords)>0)
{
    foreach($arRecords as $arRecord)
    {
        $progIds[] = $arRecord["UF_PROG"];
    }
    
    $arSelect = array("ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE", "PREVIEW_PICTURE");
    $arProgs = CProg::getList(array("ID"=>$progIds), $arSelect);
    $arProgsSorted = array();
    foreach($arProgs as $arProg)
    {
        $arProgsSorted[$arProg["ID"]] = $arProg;
    }
    unset($arProgs);
    unset($progIds);
}

$arResult["RECORDS"] = array();
foreach($arRecords as $arRecord)
{
    $arRecord["PROG"] = $arProgsSorted[$arRecord["UF_PROG"]];
    $arRecord["PROG"]["NAME"] = CProgTime::cutName($arRecord["PROG"]["NAME"]);
    $arRecord["PROG"]["PICTURE"] = CDev::resizeImage($arRecord["PROG"]["PREVIEW_PICTURE"], 300, 300);
    $arResult["RECORDS"][] = $arRecord;
}*/

$arResult["RECORDS"] = array();
foreach($arRecords as $arRecord)
{
    $arRecord["NAME"] = CProgTime::cutName($arRecord["NAME"]);
    $arRecord["PICTURE"] = CDev::resizeImage($arRecord["UF_PICTURE"], 300, 300);
    $arResult["RECORDS"][] = $arRecord;
}

//CDev::pre($arResult["RECORDS"]);

$this->IncludeComponentTemplate();
?>