
<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @var array $arLangMessages
 * @var array $templateData
 *
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $parentTemplateFolder
 * @var string $templateName
 * @var string $componentPath
 *
 * @var CDatabase $DB
 * @var CUser $USER
 * @var CMain $APPLICATION
 */
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(TRUE);
?>
<div class="authorize-overlay is-reset-overlay" data-module="reset-overlay">
	<div class="overlay-content">
		<h4 class="overlay-title"><?=GetMessage('AUTH_RECOVERY_PASSWORD')?></h4>
		<div class="steps">
			<div class="step fade in active">
        		<p><?=GetMessage('AUTH_RECOVERY_TITLE_TEXT')?></p>
        		<form action="<?= $templateFolder ?>/ajax.php">
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                	<?endif;?>
                	<input type="hidden" name="TYPE" value="SEND_PWD">
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <?=bitrix_sessid_post()?>
                    
        			<div class="form-group">
        				<label for="" class="sr-only"><?=GetMessage('AUTH_PHONE_OR_EMAIL')?></label>
        				<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" data-type="adaptive-field">
        			</div>
        			<div class="form-actions">
        				<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
        					<span class="default-state init-state"><?=GetMessage('AUTH_SEND_PASS')?></span>
        					<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
        					<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
        				</button>
        				<a href="#" class="form-subaction-link" data-type="signin-handler-link"><?=GetMessage('AUTH_REMEMBER_PASS')?></a><span class="inline-divider">|</span><a href="#" class="form-subaction-link" data-type="reset-code-handler-link"><?=GetMessage('AUTH_HAVE_CODE')?></a>
        			</div>
        		</form>
            </div>
            <div class="step fade">
				<form action="<?= $templateFolder ?>/ajax_change_password.php" data-type="reset-code-form">
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <input type="hidden" name="USER_EMAIL" value="" data-type="adaptive-field-hidden" />
                    <?=bitrix_sessid_post()?>
                    
					<div class="form-group">
						<label for="" class="sr-only"><?=GetMessage('AUTH_CODE_TO_CHANGE_PASS')?></label>
						<input type="text" name="checkword" id="" class="form-control" placeholder="<?=GetMessage('AUTH_CODE_TO_CHANGE_PASS')?>" data-type="code-field">
					</div>
					<div class="form-group has-feedback" data-type="password-field-group">
						<label for="" class="sr-only"><?=GetMessage('AUTH_NEW_PASS')?></label>
						<input type="password" name="password" id="" class="form-control" placeholder="<?=GetMessage('AUTH_NEW_PASS')?>" data-type="password-field">
						<input type="text" class="form-control" data-type="password-visualizer" placeholder="<?=GetMessage('AUTH_NEW_PASS')?>">
						<span class="form-control-feedback">
							<a href="#" data-type="password-visibility-toggle"><span data-icon="icon-password-eye"></span></a>
						</span>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
							<span class="default-state init-state"><?=GetMessage('AUTH_CHANGE_PASS')?></span>
							<span class="done-state"><?=GetMessage('AUTH_REQUEST_SEND')?></span>
							<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
							<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
						</button>
						<a href="#" class="form-subaction-link" data-type="signin-handler-link"><?=GetMessage('AUTH_REMEMBER_PASS')?></a><span class="inline-divider">|</span><a href="#" class="form-subaction-link" data-type="reset-handler-link"><?=GetMessage('AUTH_REQUEST_SEND_AGAIN')?></a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>