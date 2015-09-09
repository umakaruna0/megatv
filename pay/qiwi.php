<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");

CModule::IncludeModule("sale");
global $USER;

$dbAccountCurrency = CSaleUserAccount::GetList(
        array(),
        array("USER_ID" => $USER->GetByID()),
        false,
        false,
        array()
    );
$arAccount = $dbAccountCurrency->Fetch();

$budget = $arAccount["CURRENT_BUDGET"];
$orderID = IntVal($_POST["orderNumber"]);    // Код заказа

// Внесем (снимем) деньги на счет
if (CSaleUserAccount::UpdateAccount(
        $USER->GetByID(),
        intval($_POST["Sum"]),
        "RUB",
        "Внесение средств на счет",
        $orderID))
    {
    ?>
    <p>На ваш счет внесены средства в размере <?=$_POST["Sum"]?> руб.<br />
    <a href="/personal/">Перейти в личный кабинет</a>.</p>
    <?  
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>