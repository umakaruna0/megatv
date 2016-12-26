<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Лицензия");
$APPLICATION->SetDirProperty("h1", "Лицензия");
$APPLICATION->SetDirProperty("h1-hide", "");
?>
<section class="user-attached-socials">
    <div class="block-body">
        <div style="display: inline-block; width: 30%; margin-bottom: 20px;">
            <img src="/upload/license.jpg" width="100%" />
        </div>
        <div style="display: inline-block; width: 50%; margin: 20px 20px 0 0; vertical-align: top;">
            <p>Услуги связи оказываются ООО «МЕГАТВ»
            на основании Лицензии № 133953 от 21/07/15,
            выданной Федеральной Службой по надзору в сфере связи,
            информационных технологий и массовых коммуникаций.</p>
        </div>
    </div>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>