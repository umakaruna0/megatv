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

if($USER->IsAuthorized())
{
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();

    $salt = substr($arUser['PASSWORD'], 0, (strlen($arUser['PASSWORD']) - 32));
    $realPassword = substr($arUser['PASSWORD'], -32);
    $old_password = md5($salt.$_POST['old-password']);

    if($old_password!=$realPassword)
    {
        $result['errors']["old-password"]="Старый пароль введен не правильно!";
    }

    $password=htmlspecialcharsbx($_POST['new-password']);
    $password2=htmlspecialcharsbx($_POST['new-password2']);

    if(strlen($password)<6 || strlen($password2)<6)
    {
        $result['errors']["new-password"] = "Длина пароля должна быть не менее 6 символов!";
    }

    if($password!=$password2)
    {
        $result['errors']["new-password"] = "Пароли не совпадают!";
    }

    if(count($result['errors'])==0)
    {
        $cuser = new CUser;
        $arFields = Array(
            "PASSWORD" => $password,
            "CONFIRM_PASSWORD" => $password
        );
        $cuser->Update($USER->GetID(), $arFields);
        
        $result['status'] = true;
        $result['message'] = "<font style='color:green'>Пароль успешно изменен.</font>";
    }else{
        $result['status'] = false;
    }
}
        
exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>