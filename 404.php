<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404", "Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404 Not Found");
?>
<div style="margin: 100px auto 0; text-align: center;">
    <h1>404</h1>
    <h2>Страница не найдена.</h2>
    <div class="sitemap">
        <?
        $file = file_get_contents('sitemap.html', FILE_USE_INCLUDE_PATH); 
        echo $file;
        ?>
    </div>
    <div style="clear: both;"></div>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>