<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = false;
$result['message'] = '';
$result['errors'] = array();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || htmlspecialcharsbx($_POST["TYPE"])!="REGISTRATION" || !check_bitrix_sessid()) 
{
    $result['errors']["USER_NAME"] = "Сессия не действительна!";
}

/*if(htmlspecialcharsbx($_POST["TYPE"])!="REGISTRATION" || !check_bitrix_sessid())
{
    $result['errors']["USER_NAME"] = "Сессия не действительна и тип!";
}*/

if(!$USER->IsAuthorized() && count($result['errors'])==0)
{
    $NAME = htmlspecialcharsbx(strip_tags($_POST["USER_NAME"]));
    $LAST_NAME = htmlspecialcharsbx(strip_tags($_POST["USER_LAST_NAME"]));
    $SECOND_NAME = htmlspecialcharsbx(strip_tags($_POST["USER_SECOND_NAME"]));
    $BIRTHDAY = htmlspecialcharsbx(strip_tags($_POST["USER_PERSONAL_BIRTHDAY"]));
    $EMAIL = htmlspecialcharsbx(strip_tags($_POST["USER_EMAIL"]));
    $PASS_1 = htmlspecialcharsbx(strip_tags($_POST["USER_PASSWORD"]));
    $PASS_2 = htmlspecialcharsbx(strip_tags($_POST["USER_CONFIRM_PASSWORD"]));
    $AGREE = htmlspecialcharsbx(strip_tags($_POST["AGREE"]));
    
    if(!check_email($EMAIL))
    {
        $result['errors']["USER_EMAIL"] = "Не верный формат данных";
    }else{
        $rsUsers = CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$EMAIL));
        while($rsUsers->NavNext(true, "f_"))
        {
            $result['errors']["USER_EMAIL"] = "Введенный email уже есть на сайте";
        }
    }
    
    if(empty($NAME))
    {
        $result['errors']["USER_NAME"] = "Введите имя";
    }
    
    if(!empty($BIRTHDAY) && !preg_match("/^([0-9]{2})+([\.]{1})+([0-9]{2})+([\.]{1})+([0-9]{4})$/", $BIRTHDAY))
    {
        $result['errors']["USER_PERSONAL_BIRTHDAY"] = "Не верный формат данных";
    }

    if(strlen($PASS_1)<6 || strlen($PASS_2)<6)
    {
        $result['errors']["USER_PASSWORD"] = $result['errors']["USER_CONFIRM_PASSWORD"] = "Длина пароля должна быть не менее 6 символов!";
    }

    if($password!=$password2)
    {
        $result['errors']["USER_PASSWORD"] = $result['errors']["USER_CONFIRM_PASSWORD"] ="Пароли не совпадают!<br />";
    }
    
    if($AGREE!="on")
    {
        $result['errors']["AGREE"] = "Примите условия договора оферты";
    }
    
    if(count($result['errors'])==0)
    {
        global $USER;
        COption::SetOptionString("main","captcha_registration","N");
        
        $default_group = COption::GetOptionString("main", "new_user_registration_def_group");
        if(!empty($default_group))
            $arrGroups = explode(",", $default_group);
        
        $user = new CUser;
        $arFields = Array(
            "NAME"                  => $NAME,
            'LAST_NAME'             => $LAST_NAME,
            "SECOND_NAME"           => $SECOND_NAME,
        	"LOGIN"             	=> $EMAIL,
        	"LID"               	=> SITE_ID,
        	"ACTIVE"            	=> "Y",
        	"PASSWORD"          	=> $PASS_1,
        	"CONFIRM_PASSWORD"  	=> $PASS_1,
        	"EMAIL"			        => $EMAIL,
            "GROUP_ID"              => $arrGroups,
            "PERSONAL_BIRTHDAY"     => $BIRTHDAY
        );
        $ID = $user->Add($arFields);

        $result['status'] = true;
        $result['message'] = "<font style='color:green'>На ваш email высланы регистрационные данные!!!</font><br />";
        $USER->Login($EMAIL, $PASS_1, 'Y');

        COption::SetOptionString("main", "captcha_registration", "Y");
    }else{
        $result['status'] = false;
        $result['message'] = $html;
    }
}

exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>