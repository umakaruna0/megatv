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
$result['status'] = "error";
$result['errors'] = array();

//$result['POST'] = $_POST;

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || !check_bitrix_sessid()) 
{
    $result['errors']["USER_EMAIL"] = "Сессия не действительна!";
}

if(!$USER->IsAuthorized() && count($result['errors'])==0)
{
    $email = htmlspecialcharsbx(strip_tags($_POST["USER_EMAIL"]));
    $phone = preg_replace("/[^0-9]/", '', $email);
    $chekword = htmlspecialcharsbx($_POST["CHECKWORD"]);

    if(!CDev::check_email($email) && !CDev::check_phone($phone))
    {
        $result['errors']["USER_EMAIL"] = "Неверный формат данных";
    }else{
        
        $PASS_1 = mb_substr(md5(uniqid(rand(),true)), 0, 8);
        
        if(CDev::check_phone($phone))
        {
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("PERSONAL_PHONE" =>$phone), array("SELECT"=>array("UF_PHONE_CHECKWORD", "ID")));
            if($arUser = $rsUsers->GetNext())
            {
                if($arUser["UF_PHONE_CHECKWORD"]==$chekword && !empty($arUser["UF_PHONE_CHECKWORD"]))
                {
                    $cuser = new CUser;
                    $cuser->Update($arUser["ID"], array(
                        "ACTIVE" => "Y",
                        "UF_PHONE_CHECKWORD" => ""
                    ));
                    
                    $cuser = new CUser;
                    $cuser->Update($arUser["ID"], array(
                        "PASSWORD"          	=> $PASS_1,
                        "CONFIRM_PASSWORD"  	=> $PASS_1,
                    ));
                    
                    $text = "Ваш логин - ".$phone.", пароль - ".$PASS_1;
                    CEchogroupSmsru::Send($phone, $text);
                    
                    $USER->Authorize($arUser["ID"], true);
                    
                    $result['status'] = "success";
                }else{
                    $result['errors']["CHECKWORD"] = "Введите правильный код";
                }
            }
        }else{
            
            $chekword = trim($_POST["CHECKWORD"]);
            
            $rsUser = CUser::GetByLogin($email);
        	if($arResult["USER"] = $rsUser->GetNext())
        	{
                //$result['message'] = $arResult["USER"]["~CONFIRM_CODE"];
                if(strlen($chekword) > 0 && $chekword == $arResult["USER"]["~CONFIRM_CODE"])
                {
                    $obUser = new CUser;
				    $obUser->Update($arResult["USER"]["ID"], array(
                        "ACTIVE" => "Y", 
                        "CONFIRM_CODE" => "",
                        "PASSWORD"          	=> $PASS_1,
                        "CONFIRM_PASSWORD"  	=> $PASS_1,
                    ));
                    
                    //Меняем пароль и отправляем письмо с данными
                    $arFields = $arResult["USER"];
                    $arFields["PASSWORD"] = $PASS_1;
                    
                    $event = new CEvent;
                    $event->SendImmediate("USER_INFO_AFTER_CONFIRM", SITE_ID, $arFields);
                    
                    $USER->Authorize($arResult["USER"]["ID"], true);
                    
                    $result['status'] = "success";
                }
        	}
            
        }
    }

}else{
    $result['message'] = 'Вы уже авторизованны.';
}

exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>