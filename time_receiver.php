<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

$f = fopen('php://input', 'r');
$json = stream_get_contents($f);

if(isset($json) && !empty($json)){
    $rdata = $json;
}elseif(isset($_POST) && !empty($_POST)){
    $rdata = $_POST;
}elseif(isset($_REQUEST) && !empty($_REQUEST)){
    $rdata = $_REQUEST;
}elseif(isset($_GET) && !empty($_GET)){
    $rdata = $_GET;
}

function isValidJSON($str) 
{
    json_decode($str, true);
    return json_last_error() == JSON_ERROR_NONE;
}

if(isset($rdata))
{
    //\CDev::log($rdata, false, "/time_".date("Ymd").".txt");
    
    $rdata = trim($rdata);
    
    if (strlen($rdata) > 0/* && isValidJSON($rdata)*/) 
    { 
        $data = array(
            'DATA'       => json_decode($rdata, true),
        );
        
        //\CDev::log($data["DATA"], false, "/time_".date("Ymd").".txt");
        
        foreach($data["DATA"] as $arData)
        {
            $arFields = array(
                "UF_START" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arData["starts"])), 'Y-m-d H:i:s' ),
                "UF_END" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arData["ends"])), 'Y-m-d H:i:s' ),
                "UF_CHANNEL_ID" => (int) $arData["channel_id"],
                "UF_EX_ID" => (int) $arData["id"],
                "UF_KNOW_ID" => (int) $arData["known_id"]
            );
            
            $result = \Hawkart\Megatv\TimeTable::add($arFields);
            if ($result->isSuccess())
            {
            }else{
                //\CDev::log($result->getErrorMessages(), false, "/time_".date("Ymd").".txt");
            }
            
        }
    }

    echo "200 OK";
}

die()
?>