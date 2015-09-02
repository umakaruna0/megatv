<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;
?>
<div class="flex-row user-services-top-row">

    <?$APPLICATION->IncludeComponent("hawkart:subscription.services", "", Array(), false);?>

	<section class="user-balance">
    
        <?/*$APPLICATION->IncludeComponent(
        	"bitrix:asd.money.prepaid", 
        	"visual", 
        	array(
        		"COMPONENT_TEMPLATE" => "visual",
        		"ALLOWED_CURRENCY" => array(
        			0 => "RUB",
        		),
        		"DEFAULT_CURRENCY" => "RUB",
        		"COMISSION" => "0",
        		"CART_PAGE" => "",
        		"PAY_IMMED" => "Y",
        		"SET_TITLE" => "N",
        		"PERSON_TYPE" => "1"
        	),
        	false
        );*/?>
    
		<div class="block-header">
			<h3 class="block-title">Баланс</h3>
		</div>
		<div class="block-body">
			<div class="account-balance">
				<span data-icon="icon-balance"></span><small>На счету:</small> <?=$APPLICATION->ShowViewContent('user_budget');?> Р
				<a href="#" class="btn btn-primary btn-block">Пополнить счет</a>
			</div>
            
            <?$APPLICATION->IncludeComponent(
            	"bitrix:asd.money.transacts",
            	"transactions",
            	Array(
            		"COMPONENT_TEMPLATE" => ".default",
            		"PATH_TO_ORDER" => "",
            		"PAGE_COUNT" => "2",
            		"PAGER_TEMPLATE" => "",
            		"SET_TITLE" => "N"
            	)
            );?>
            
		</div>
	</section>
</div>

<?$APPLICATION->IncludeComponent("hawkart:subscription.channels", "", Array(), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>