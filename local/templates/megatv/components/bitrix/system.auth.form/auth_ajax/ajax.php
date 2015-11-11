<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();


if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY) && htmlspecialcharsbx($_POST["TYPE"])=="AUTH" && check_bitrix_sessid()) 
{
    $login = htmlspecialcharsbx($_POST["USER_PASSWORD"]);
    $password = htmlspecialcharsbx($_POST["USER_LOGIN"]);
    $arAuthResult = $USER->Login($login, $password, "Y");
    
    if(!$USER->IsAuthorized())
    {
        $result['status'] = 'error';
        $result['errors'] = array(/*"USER_PASSWORD"=>"", "USER_LOGIN"=>""*/);
    }else{
        $result['status'] = 'ok';
    }
}

exit(json_encode($result));
?>