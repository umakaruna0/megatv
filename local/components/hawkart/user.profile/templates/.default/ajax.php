<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = false;
$result['message'] = '';
$result['errors'] = array();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) ||  !check_bitrix_sessid()) 
{
    $result['errors']["old-password"] = "Сессия не действительна!";
}

$action = htmlspecialcharsbx($_REQUEST["action"]);

if($USER->IsAuthorized())
{
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();

    $arPost = $_REQUEST["USER"];

    if($action == "profile")
    {
        foreach($arPost as &$value)
        {
            $value = htmlspecialcharsbx(trim($value));
        }
        $arPost["PERSONAL_PHONE"] = preg_replace("/[^0-9]/", '', $arPost["PERSONAL_PHONE"]);
        
        if(!preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $arPost["EMAIL"]))
        {
            $result['errors']['USER[EMAIL]'] = "Неправильный формат электроной почты.";
        }

        if(!empty($arPost["EMAIL"]))
        {
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$arPost["EMAIL"], "!ID"=>$arUser["ID"]));
            while($rsUsers->NavNext(true, "f_"))
            {
                $result['errors']['USER[EMAIL]'] = "Такая электроная почта существует на сайте.";
            }
        }
        
        if(empty($arPost["NAME"]))
        {
            $result['errors']['USER[NAME]'] = "Введите имя.";
        }
        if(empty($arPost["LAST_NAME"]))
        {
            $result['errors']['USER[LAST_NAME]'] = "Введите фамилию.";
        }
        if(empty($arPost["SECOND_NAME"]))
        {
            $result['errors']['USER[SECOND_NAME]'] = "Введите отчество.";
        }
        
        if(!empty($arPost["PERSONAL_BIRTHDAY"]) && !preg_match("/^([0-9]{2})+([\.]{1})+([0-9]{2})+([\.]{1})+([0-9]{4})$/", $arPost["PERSONAL_BIRTHDAY"]))
        {
            $result['errors']["USER[PERSONAL_BIRTHDAY]"] = "Не верный формат.";
        }
        
        if(!empty($arPost["PERSONAL_PHONE"]) && !preg_match("/^([0-9]{11})$/", $arPost["PERSONAL_PHONE"]))
        {
            $result['errors']["USER[PERSONAL_PHONE]"] = "Не верный формат.";
        }
        
        if(count($result['errors'])==0)
        {
            $сuser = new CUser;
            $fields = Array(
                "NAME"              => $arPost["NAME"],
                "LAST_NAME"         => $arPost["LAST_NAME"],
                "SECOND_NAME"       => $arPost["SECOND_NAME"],
                "EMAIL"             => $arPost["EMAIL"],
                "PERSONAL_BIRTHDAY" => $arPost["PERSONAL_BIRTHDAY"],  
                "PERSONAL_PHONE"    => $arPost["PERSONAL_PHONE"]
            );


            if(empty($arUser["EMAIL"]) && !empty($arPost["EMAIL"]))
            {
                CUserEx::capacityAdd($arUser["ID"], 1);   // за мэйл +1ГБ
            }
            
            if(empty($arUser["PERSONAL_PHONE"]) && !empty($arPost["PERSONAL_PHONE"]))
            {
                CUserEx::capacityAdd($arUser["ID"], 1);   // за ттееллееффоонн +1ГБ
            }
            

            $сuser->Update($arUser["ID"], $fields);
            $strError = $сuser->LAST_ERROR;
                     
            $result['status'] = true;
            $result['message'] = "<font style='color:green'>Данные успешно изменены.</font>";
        }
    }
    
    if($action == "passport")
    {
        $arPost = $arPost["PASSPORT"];
        
        foreach($arPost as &$value)
        {
            $value = htmlspecialcharsbx(trim($value));
        }

        if(!preg_match("/^([0-9]{4})$/", $arPost["SERIA"]))
        {
            $result['errors']["USER[PASSPORT][SERIA]"] = "Не верный формат.";
        }
        
        if(!preg_match("/^([0-9]{6})$/", $arPost["NUMBER"]))
        {
            $result['errors']["USER[PASSPORT][NUMBER]"] = "Не верный формат.";
        }
        
        if(empty($arPost["WHO_ISSUED"]))
        {
            $result['errors']['USER[PASSPORT][WHO_ISSUED]'] = "Заполните поле.";
        }
        
        if(!preg_match("/^([0-9]{2})+([\.]{1})+([0-9]{2})+([\.]{1})+([0-9]{4})$/", $arPost["WHEN_ISSUED"]))
        {
            $result['errors']["USER[PASSPORT][WHEN_ISSUED]"] = "Не верный формат.";
        }
        
        if(empty($arPost["CODE_DIVISION"]))
        {
            $result['errors']['USER[PASSPORT][CODE_DIVISION]'] = "Заполните поле.";
        }
        
        if(empty($arPost["ADDRESS"]))
        {
            $result['errors']['USER[PASSPORT][ADDRESS]'] = "Заполните поле.";
        }
        
        if(count($result['errors'])==0)
        {
            $arrFilter = array(
                "IBLOCK_ID" => PASSPORT_IB,
                "ACTIVE" => "Y",
                "PROPERTY_USER_ID" => $USER->GetID()
            );
            $arSelect = array("ID");
            $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect);
        	$arPassport = $rsRes->GetNext();
            
            $el = new CIBlockElement;
            $PROP = array();
            $PROP["USER_ID"] = $USER->GetID();
            $PROP["SERIA_NUMBER"] = $arPost["SERIA"]." ".$arPost["NUMBER"];
            $PROP["WHEN_ISSUED"] = $arPost["WHEN_ISSUED"];
            $PROP["CODE_DIVISION"] = $arPost["CODE_DIVISION"];
            
            $arLoadProductArray = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"      => PASSPORT_IB,
                "PROPERTY_VALUES"=> $PROP,
                "NAME"           => "Паспорт №".$USER->GetID(),
                "ACTIVE"         => "Y",
                "PREVIEW_TEXT"   => $arPost["WHO_ISSUED"],
                "DETAIL_TEXT"    => $arPost["ADDRESS"]
            );
            
            if(isset($arPassport["ID"]))
            {
                $el->Update($arPassport["ID"], $arLoadProductArray);
            }else{
                $el->Add($arLoadProductArray);
            }
   
            $result['status'] = true;
            $result['message'] = "<font style='color:green'>Данные успешно изменены.</font>";
        }
    }
    
    if($action=="avatar-upload")
    {
        require('UploadHandler.php');
        $upload_handler = new UploadHandler();
    }
}
        
exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>