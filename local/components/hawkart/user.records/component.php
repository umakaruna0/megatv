<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
$arResult["RECORDS"] = array();

$result = \Hawkart\Megatv\RecordTable::getList(array(
    'filter' => array(
        "=UF_USER_ID" => $USER->GetID(), 
        "!UF_URL" => false
    ),
    'select' => array(
        "ID", "UF_DATE_START", "UF_DATE_END", "UF_PROG_ID", "UF_WATCHED", "UF_PROGRESS_PERS",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", "UF_URL", "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_CODE",
        "UF_ID" => "UF_PROG.UF_EPG_ID"
    ),
    'order' => array("UF_DATE_END"=>"DESC")
));
while ($arRecord = $result->fetch())
{
    $arRecord["UF_NAME"] = \Hawkart\Megatv\ProgTable::getName($arRecord);
    $arRecord["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true);
    $arRecord["DETAIL_PAGE_URL"] = "/channels/".$arRecord["UF_CHANNEL_CODE"]."/".$arRecord["UF_ID"]."/";
    
    if(!empty($arRecord["UF_CATEGORY"]))
        $arCats[] = $arRecord["UF_CATEGORY"];
        
    $arResult["RECORDS"][] = $arRecord;
}

$arCats = array_unique($arCats);

$arResult["CATEGORIES"] = array();
foreach($arCats as $category)
{
    $arParams = array("replace_space"=>"-", "replace_other"=>"-");
    $str = \CDev::translit($category, "ru", $arParams);
    $arResult["CATEGORIES"][$category] = $str; 
}

$this->IncludeComponentTemplate();
?>