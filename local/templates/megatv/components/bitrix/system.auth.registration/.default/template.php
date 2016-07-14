<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="authorize-overlay is-signup-overlay" data-module="signup-overlay">
	<div class="overlay-content">
		<h4 class="overlay-title"><?=GetMessage('AUTH_REGISTER')?></h4>

        <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/social-auth.php");?>
        
		<span class="divider"><span><?=GetMessage('AUTH_OR')?></span></span>
		<div class="steps">
			<div class="step fade in active">
				<form action="<?= $templateFolder ?>/ajax.php" method="post">
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                	<?endif;?>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                	<input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <?=bitrix_sessid_post()?>
                    
					<div class="form-group">
						<label for="" class="sr-only"><?=GetMessage('AUTH_PHONE_OR_EMAIL')?></label>
						<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" data-type="adaptive-field">
					</div>
					<div class="checkbox">
						<label for="_id-singup--chackbox"><input type="checkbox" name="AGREE" id="_id-singup--chackbox"><span><?=GetMessage('AUTH_AGREE')?> <a href="#"><?=GetMessage('AUTH_AGREE_OFERTA')?></a></span></label>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
							<span class="default-state init-state"><?=GetMessage('AUTH_REGISTER_BUTTON')?></span>
							<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
							<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
						</button>
						<a href="#" class="form-subaction-link" data-type="reset-handler-link"><?=GetMessage('AUTH_RECOVERY_PASSWORD')?></a>
					</div>
				</form>
			</div>
			<div class="step fade">
				<p><?=GetMessage('AUTH_CONFIRM_TEXT_1')?></p>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:system.auth.confirmation",
                    ".default",
                    Array()
                );?>
			</div>
		</div>
	</div>
</div>