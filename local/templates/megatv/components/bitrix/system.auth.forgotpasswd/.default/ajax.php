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

if(!$USER->IsAuthorized())
{
    $html="";
    
    if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || htmlspecialcharsbx($_POST["TYPE"])!="SEND_PWD" || !check_bitrix_sessid()) 
    {
        $html = "Сессия не действительна!";
    }
    
    $emailTo = trim(htmlspecialcharsbx($_POST['USER_EMAIL']));
    
    if(empty($html))
    {
        $filter = Array("ACTIVE" => "Y", "=EMAIL"  => $emailTo);
        $user = CUser::GetList(($by="timestamp_x"), ($order="desc"), $filter)->Fetch();
        if(intval($user["ID"]) > 0 && !empty($emailTo))
        {
            $arResult = $USER->SendPassword($user["LOGIN"], $user["EMAIL"]);
            if($arResult["TYPE"] == "OK")
                $result['message'] = "<font style='color:green'>На ваш email придет сообщение с необходимыми данными.</font>";
                
            $result['status'] = true;
        }else{
            $result['message'] = 'Пользователь с такие e-mail адресом не найден.';
        }
    }else{
        $result['status'] = false;
        $result['message'] = $html;
    }
}else{
    $result['message'] = 'Вы уже авторизованны.';
}

$result["mail"] = $emailTo;

exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>