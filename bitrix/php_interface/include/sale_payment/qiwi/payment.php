<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
$Sum = CSalePaySystemAction::GetParamValue("SHOULD_PAY");
$orderNumber = CSalePaySystemAction::GetParamValue("ORDER_ID");
$Sum = number_format($Sum, 2, ',', '');
?>
<font class="tablebodytext">
    Сумма к оплате по счету: <b><?=$Sum?> р.</b><br />
    <br />
</font>
<form name="ShopForm" action="/pay/qiwi.php" method="post">
    <input name="orderNumber" value="<?=$orderNumber?>" type="hidden">
    <input name="Sum" value="<?=$Sum?>" type="hidden">
    <input name="BuyButton" value="Оплатить" type="submit">
    <p><font class="tablebodytext"><b>Обратите внимание:</b> если вы откажетесь от покупки, для возврата денег вам придется обратиться в магазин.</font></p>
</form>