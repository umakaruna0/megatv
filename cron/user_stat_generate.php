<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

echo $dstart = date("H:i:s")."\r\n";

mail("hawkart@rambler.ru", "User stat generate started", "start of generate stat");


$dateStart = date("Y-m-d H:i:s");
$dateEnd = date("Y-m-d 00:00:00", strtotime("+1 day", strtotime($dateStart)));
$arDates = array(
    array(
        "start" => $dateStart,
        "end" => $dateEnd
    ),
    array(
        "start" => date("Y-m-d 00:00:00", strtotime("+1 day", strtotime($dateStart))),
        "end" => date("Y-m-d 00:00:00", strtotime("+1 day", strtotime($dateEnd)))
    )
);

$arProgsByRating = \Hawkart\Megatv\ProgTable::getProgsByRating();
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), Array("ACTIVE" => "Y"), array("FIELDS"=>array("ID")));
while($arUser = $rsUsers->GetNext())
{
    $arStatistic = \Hawkart\Megatv\CStat::getByUser($arUser["ID"]);
        
    $arRecommend = array(
        "by_ganres" => \Hawkart\Megatv\CStat::getProgsByGanre($arProgsByRating, $arStatistic),
        "by_topics" => \Hawkart\Megatv\CStat::getProgsByTopic($arProgsByRating, $arStatistic),
        "by_users" => \Hawkart\Megatv\CStat::getTopRateProg($arProgsByRating),
        "by_records" => \Hawkart\Megatv\CStat::getTopRateSerialByUser($arUser["ID"], $arStatistic)
    );
    $json = json_encode($arRecommend);
    
    $ids = array();
    foreach($arRecommend as $key=>$arStat)
    {
        $ids = array_merge($ids, array_slice( $directors, 1, 100));
    }
    
    $ids = array_unique($ids);
    
    $schedule_ids = array();
    foreach($arDates as $arDate)
    {
        $arFilter = array(
            "=UF_PROG.UF_ACTIVE" => 1,
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($arDate["start"], 'Y-m-d H:i:s'),
            "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($arDate["end"], 'Y-m-d H:i:s'),
            "!=UF_PROG.UF_CATEGORY" => "Новости"
        );
        $arGetList = array(
            'filter' => $arFilter,
            'select' => array("ID", "SID" => "UF_PROG.UF_EPG_ID"),
            'limit' => 300,
            'order' => array("UF_DATE_START" => "ASC")
            //'order' => array("UF_PROG.UF_RATING" => "DESC")
        );
        $result = \Hawkart\Megatv\ScheduleTable::getList($arGetList);
        while ($arSchedule = $result->fetch())
        {
            $schedule_ids[] = $arSchedule["ID"];
            //$schedule_ids[$arSchedule["SID"]] = $arSchedule["ID"];
        }
    }
    $schedule_ids = array_unique($schedule_ids);
    
    \Hawkart\Megatv\CStat::saveRecommendSchedules($arUser["ID"], json_encode($schedule_ids), $DOCUMENT_ROOT);
    \Hawkart\Megatv\CStat::saveRecommend($arUser["ID"], $json);
}

echo date("H:i:s")."\r\n";
mail("hawkart@rambler.ru", "User stat generate finished", "finish of generate stat");
die();
?>