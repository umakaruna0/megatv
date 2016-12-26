<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

//include lang file
CComponentUtil::__IncludeLang(dirname($_SERVER["SCRIPT_NAME"]), "/ajax.php");

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = 'error';
$result['message'] = '';
$result['errors'] = array();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || htmlspecialcharsbx($_POST["TYPE"])!="REGISTRATION" || !check_bitrix_sessid()) 
{
    $result['errors']["USER_NAME"] = GetMessage('AUTH_ERROR_SESSION_EXPIRED');
}

if(!$USER->IsAuthorized() && count($result['errors'])==0)
{
    $EMAIL = htmlspecialcharsbx(strip_tags($_POST["USER_EMAIL"]));
    $AGREE = htmlspecialcharsbx(strip_tags($_POST["AGREE"]));
    $password = htmlspecialcharsbx($_POST["USER_PASSWORD"]);
    
    $phone = preg_replace("/[^0-9]/", '', $EMAIL);

    if(!\CDev::check_email($EMAIL) && !\CDev::check_phone($phone))
    {
        $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_DATA_FORMAT');
    }else{
        
        if(\CDev::check_phone($phone))
        {
            $rsUsers = \CUser::GetList(($by="EMAIL"), ($order="desc"), Array("PERSONAL_PHONE" =>$phone));
            if($arUser = $rsUsers->GetNext())
            {
                if($arUser["ACTIVE"]=="N")
                {
                    $result["status"] = "need_confirm";
                    exit(json_encode($result));
                }
                $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_PHONE_EXIST');
            }
        }else{
            $rsUsers = \CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$EMAIL));
            if($arUser = $rsUsers->GetNext())
            {
                if($arUser["ACTIVE"]!="Y")
                {
                    $result["status"] = "need_confirm";
                    exit(json_encode($result));
                }
                $result['errors']["USER_EMAIL"] = GetMessage('AUTH_ERROR_EMAIL_EXIST');
            }
        }
    }

    if($AGREE!="on")
    {
        $result['errors']["AGREE"] = GetMessage('AUTH_ERROR_AGREE');
    }
    
    if(strlen($password)<6)
    {
        $result['errors']["USER_PASSWORD"] = GetMessage('AUTH_ERROR_PASSWORD');
    }
    
    if(count($result['errors'])==0)
    {
        global $USER;
        COption::SetOptionString("main","captcha_registration", "N");
        
        $default_group = COption::GetOptionString("main", "new_user_registration_def_group");
        if(!empty($default_group))
            $arrGroups = explode(",", $default_group);
        
        $user = new CUser;
        $arFields = Array(
        	"LOGIN"             	=> $EMAIL,
        	"LID"               	=> SITE_ID,
        	"ACTIVE"            	=> "N",
        	"PASSWORD"          	=> $password,
        	"CONFIRM_PASSWORD"  	=> $password,
        	"EMAIL"			        => $EMAIL,
            "GROUP_ID"              => $arrGroups,
            "CHECKWORD"             => md5(CMain::GetServerUniqID().uniqid()),
            "CONFIRM_CODE"          => randString(8),
            "USER_IP"               => $_SERVER["REMOTE_ADDR"],
            "USER_HOST"             => @gethostbyaddr($_SERVER["REMOTE_ADDR"])
        );
        
        //Если ввели телефон
        if(CDev::check_phone($phone))
        {
            $arFields["PERSONAL_PHONE"] = $phone;
            $arFields["EMAIL"] = $arFields["LOGIN"] = $phone."@megatv.su";
            $arFields["UF_PHONE_REG"] = "Y";
        }
        
        $USER_ID = $user->Add($arFields);
        
		if(intval($USER_ID)>0)
        {
            CUserEx::subcribeOnFreeChannels($USER_ID);
            
            $arFields["USER_ID"] = $USER_ID;
            $event = new CEvent;
    		$event->SendImmediate("NEW_USER", SITE_ID, $arFields);
            
            if(CDev::check_phone($phone))
            {
                $checkword = mb_substr(md5(uniqid(rand(),true)), 0, 8);
                $cuser = new CUser;
                $cuser->Update($USER_ID, array(
                    "UF_PHONE_CHECKWORD" => $checkword
                ));
                
                $text = GetMessage('AUTH_ACTIVATE_CODE_TEXT').$checkword;
                CEchogroupSmsru::Send($phone, $text);
                $result['message'] = "<font style='color:green'>".GetMessage('AUTH_REGISTER_SUCCESS_TEXT_1')."</font><br />";
            }else{
                
                //Для подтверждения регистрации перейдите по следующей ссылке:
                //http://#SERVER_NAME#/auth/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#
                
                $event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arFields);  //на почту письмо для подтверждения
                $result['message'] = "<font style='color:green'>".GetMessage('AUTH_REGISTER_SUCCESS_TEXT_2')."</font><br />";
            
                CUserEx::capacityAdd($USER_ID, 1);   // за мэйл +1ГБ
            }
        }

        $result['status'] = "success";
        
        //Бонус за регистрацию
        CUserEx::capacityAdd($USER_ID, BONUS_FOR_REGISTRATION);

        COption::SetOptionString("main", "captcha_registration", "Y");
    }else{
        $result['status'] = 'error';
        $result['message'] = $html;
    }
}

exit(json_encode($result));
?>