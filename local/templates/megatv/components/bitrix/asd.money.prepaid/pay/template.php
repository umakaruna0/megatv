<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

CUtil::InitJSCore(array('jquery'));
?>

<?foreach ($arResult['ACCOUNT'] as $arAccount):?>
<?endforeach;?>

<div class="modal fade paymethod-modal" id="paymethod-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Пополнение баланса</h3>
				<div class="paymethod-modal-balance"><span data-icon="icon-balance" data-size="small"></span> На счету: <?= round($arAccount['CURRENT_BUDGET'], 0)?> <span data-icon="icon-ruble"></span></div>
			</div>
			<a href="#" onmouseup="$(document.body).removeClass('payment-opened')" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
            
            <?
            //POST_FORM_ACTION_URI
            $url = SITE_TEMPLATE_PATH."/ajax/get_payment.php";
            ?>
            
			<form method="post" action="<?= $url?>" class="asd-prepaid-form">
                
                <input type="hidden" name="prepaid_money" value="Y" />
                <input type="hidden" id="bx-asd-baseformat" value="<?= $arResult['CURRENCIES'][$arResult['LANG_CURRENCY']]['FORMAT_STRING']?>" />
                <input type="hidden" id="bx-asd-comission" value="<?= $arParams['COMISSION']?>" />
                <input type="hidden" name="account" id="bx-asd-account" value="<?=$arAccount['CURRENCY']?>" data-factor="<?= $arResult['CURRENCIES'][$arAccount['CURRENCY']]['FACTOR']?>"/>
                <?= bitrix_sessid_post()?>
            
				<div class="form-group has-feedback">
					<label for="_id-paymethod--summ">Введите сумму для зачисления: </label>
					<input type="text" name="amount" id="bx-asd-amount" value="<?= $arResult['REQUEST_AMOUNT']?>" class="form-control" data-type="paymethod-field">
					<span class="form-control-feedback"><span data-icon="icon-ruble"></span></span>
				</div>
				<div class="radio-group">
					<label for="">Выберите способ платежа:</label>
                    
                    <?//if ($arResult['ERROR'] != '') ShowError($arResult['ERROR']);?>
                    
                    <?
                    function getIconByName($name)
                    {
                        switch($name)
                        {
                            case "Банковские карты":
                                return "cards";
                            break;
                            case "МегаФон":
                                return "megafon";
                            break;
                            case "МТС":
                                return "mts";
                            break;
                            case "Билайн":
                                return "beeline";
                            break;
                            case "Яндекс.Деньги":
                                return "yandexmoney";
                            break;
                            case "WebMoney":
                                return "webmoney";
                            break;
                            case "Сбербанк Онлайн":
                                return "sberbankonline";
                            break;
                            case "Альфа-Клик":
                                return "alfaclick";
                            break;
                            case "MasterPass":
                                return "masterpass";
                            break;
                            case "Промсвязьбанк":
                                return "promsvasbank";
                            break;
                        }
                    }
                    ?>
                    
                    <?foreach ($arResult['PAY_SYSTEMS'] as $arSystem):?>
                        <?
                        $icon = getIconByName($arSystem['NAME']);
                        ?>
                        <div class="radio">
    						<label for="asd_ps_<?= $arSystem['PAY_SYSTEM_ID']?>">
    							<input type="radio" name="pay_system" id="asd_ps_<?= $arSystem['PAY_SYSTEM_ID']?>" value="<?= $arSystem['PAY_SYSTEM_ID']?>" <?if ($arResult['REQUEST_PAY_SYSTEM'] == $arSystem['PAY_SYSTEM_ID']){?> checked="checked"<?}?>>
    							<span class="overlap-bg"></span>
    							<span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
    							</span>
    							<span data-icon="icon-<?=$icon?>-paymethod"></span>
    							<span class="radio-text"><?= htmlspecialcharsbx($arSystem['NAME'])?></span>
    						</label>
    					</div>
                    <?endforeach;?>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-block" data-type="paymethod-submit" disabled>Пополнить счет</button>
				</div>
			</form>
            <div id="form-pay-request" style="display: none;"></div>
		</div>
	</div>
</div>