<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = "fail";
$result['errors'] = array();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']!=md5('ajax_'.LICENSE_KEY) || !check_bitrix_sessid()) 
{
    $result["message"] = "Сессия не действительна!";
}

$text = htmlspecialcharsbx($_REQUEST["text"]);
$prog_id = intval($_REQUEST["prog_id"]);

if($USER->IsAuthorized() && !empty($text))
{
    $USER_ID = $USER->GetID();
    
    /*if($_REQUEST["force_add"]=="y")
    {
        $arComments = array();
    }else{
        $arComments = CCommentEx::getList(array("UF_USER_ID"=>$USER_ID, "UF_TEXT"=>$text, "UF_PROG_ID" => $prog_id), array("ID"));
    }*/
    
    $rsUser = CUser::GetByID($USER_ID);
    $arUser = $rsUser->Fetch();
    
    /*if(count($arComments)>0)
    {
        $result = array(
             "status" => "warning",
             "username" => trim($arUser["NAME"]." ".$arUser["LAST_NAME"]),
             "user_photo" => CFile::GetPath($arUser["PERSONAL_PHOTO"]),
             "publish_date" => $arDATE["DD"]." ".ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"))." ".$arDATE["YYYY"],
             "comment_text" => $text,
             "message"      => "Такой комментарий уже добавлен вами."
        );
    }
    else
    {*/
        $arDATE = ParseDateTime(date("d.m.Y"), FORMAT_DATETIME);
        $res = CCommentEx::create(array(
            "TEXT" => $text,
            "PROG_ID" => $prog_id
        ));
        
        if($res===true)
        {
            $result = array(
                 "status" => "success",
                 "username" => trim($arUser["NAME"]." ".$arUser["LAST_NAME"]),
                 "user_avatar" => CFile::GetPath($arUser["PERSONAL_PHOTO"]),
                 "publish_date" => $arDATE["DD"]." ".ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"))." ".$arDATE["YYYY"],
                 "comment_text" => $text
            );
        }else{
            $result["message"] = $res;
        }
    //}
}else{
    $result["message"] = 'Введите сообщение.';
}
        
exit(json_encode($result));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>