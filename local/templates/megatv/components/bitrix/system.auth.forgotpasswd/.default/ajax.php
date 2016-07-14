<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

//include lang file
CComponentUtil::__IncludeLang(dirname($_SERVER["SCRIPT_NAME"]), "/ajax.php");


global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = "error";
$result['errors'] = array();
$result['message'] = '';

if(!$USER->IsAuthorized())
{
    $html="";
    
    if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || htmlspecialcharsbx($_POST["TYPE"])!="SEND_PWD" || !check_bitrix_sessid()) 
    {
        $html = GetMessage('AUTH_ERROR_SESSION_EXPIRED');
    }
    
    $emailTo = trim(htmlspecialcharsbx($_POST['USER_EMAIL']));
    $phone = preg_replace("/[^0-9]/", '', $emailTo);
    
    if(!CDev::check_email($emailTo) && !CDev::check_phone($phone))
    {
        $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_DATA_FORMAT');
    }
    
    if(empty($html) && count($result['errors'])==0)
    {
        if(CDev::check_phone($phone))
        {
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("PERSONAL_PHONE" =>$phone));
            if($arUser = $rsUsers->GetNext())
            {
                //отправить на телефон
                $arResult = $USER->SendPassword($arUser["LOGIN"], $arUser["EMAIL"]);
                if($arResult["TYPE"] == "OK")
                {
                    $result['message'] = "<font style='color:green'>".GetMessage('AUTH_RECOVERY_TEXT_1')."</font>";
                    
                    $PASS_1 = mb_substr(md5(uniqid(rand(),true)), 0, 8);
                    $cuser = new CUser;
                    $cuser->Update($arUser["ID"], array(
                        "UF_PHONE_CHECKWORD" => $PASS_1
                    ));
                    
                    $text = GetMessage('AUTH_CHECKWORD').$PASS_1;
                    CEchogroupSmsru::Send($phone, $text);
                    
                    $result['status'] = "success";
                }

            }else{
                $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_PHONE_NOT_EXIST');
            }
        }else{
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$emailTo));
            if($arUser = $rsUsers->GetNext())
            {
                $arResult = $USER->SendPassword($arUser["LOGIN"], $arUser["EMAIL"]);
                if($arResult["TYPE"] == "OK")
                    $result['message'] = "<font style='color:green'>".GetMessage('AUTH_RECOVERY_TEXT_2')."</font>";
                    
                $result['status'] = "success";
            }else{
                $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_EMAIL_NOT_EXIST');
            }
        }
        
    }else{
        $result['status'] = "error";
        $result['message'] = $html;
    }
}else{
    $result['message'] = GetMessage('AUTHORIZED');
}

exit(json_encode($result));
?>