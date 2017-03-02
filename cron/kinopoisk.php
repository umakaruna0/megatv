<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

$result = \Hawkart\Megatv\PeopleTable::getList(array(
    'filter' => array("UF_KINOPOISK_LINK" => false),
    'select' => array("UF_TITLE", "ID"),
    'limit' => 300
));
while ($row = $result->fetch())
{
    $url = \Hawkart\Megatv\Kinopoisk::searchByCurl("hawkart@rambler.ru", "Vfrcbvec89", $row["UF_TITLE"]);
    \Hawkart\Megatv\PeopleTable::update($row["ID"], array(
        "UF_KINOPOISK_LINK" => $url
    ));
    
    sleep(2);
}

die();        
?>