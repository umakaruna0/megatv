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
$result['message'] = '';
//$result['post'] = $_POST;

if(!$USER->IsAuthorized())
{
    $html="";
    
    if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || !check_bitrix_sessid()) 
    {
        $html = "Сессия не действительна!";
    }
    
    $emailTo = trim(htmlspecialcharsbx($_POST['USER_EMAIL']));
    $phone = preg_replace("/[^0-9]/", '', $emailTo);
    $chekword = htmlspecialcharsbx($_POST["checkword"]);
    $password = htmlspecialcharsbx($_POST["password"]);
    
    if(!CDev::check_email($emailTo) && !CDev::check_phone($phone))
    {
        $result['errors']["USER_EMAIL"] = "Неверный формат данных";
    }
    
    if(empty($html) && count($result['errors'])==0)
    {
        if(CDev::check_phone($phone))
        {
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("PERSONAL_PHONE" =>$phone), array("SELECT"=>array("UF_PHONE_CHECKWORD", "ID")));
            if($arUser = $rsUsers->GetNext())
            {
                if($arUser["UF_PHONE_CHECKWORD"]==$chekword && !empty($arUser["UF_PHONE_CHECKWORD"]))
                {
                    $cuser = new CUser;
                    $cuser->Update($arUser["ID"], array(
                        "UF_PHONE_CHECKWORD" => "",
                        "PASSWORD"          	=> $password,
                        "CONFIRM_PASSWORD"  	=> $password,
                    ));
                    $result['status'] = "success";
                }else{
                    $result['errors']["USER_EMAIL"] = "";
                }
            }else{
                $result['errors']["USER_EMAIL"] = 'Пользователь с такие телефоном не найден.';
            }
        }else{
            $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$emailTo));
            if($arUser = $rsUsers->GetNext())
            {
                $arResult = $USER->ChangePassword($arUser["LOGIN"], $chekword, $password, $password);
                if($arResult["TYPE"] == "OK")
                {
                    $result['message'] = "Пароль успешно сменен.";
                    $result['status'] = "success";
                }else{
                    $result['message'] = $arResult["MESSAGE"];
                }
            }else{
                $result['errors']["USER_EMAIL"] = 'Пользователь с такие e-mail адресом не найден.';
            }
        }
        
    }else{
        $result['status'] = "error";
    }
}else{
    $result['message'] = 'Вы уже авторизованны.';
}

exit(json_encode($result));
?>