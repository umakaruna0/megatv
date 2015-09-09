<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = false;
$result['errors'] = array();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || !check_bitrix_sessid()) 
{
    $result['errors']['text'] = "Сессия не действительна!";
}

$text = htmlspecialcharsbx($_REQUEST["text"]);
$prog_id = intval($_REQUEST["prog_id"]);

if($USER->IsAuthorized() && !empty($text))
{
    $USER_ID = $USER->GetID();
    
    $result = CCommentEx::create(array(
        "TEXT" => $text,
        "PROG_ID" => $prog_id
    ));
    
    if($result)
        $result['status'] = "success"; 
}
        
exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>