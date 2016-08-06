<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["FORM_TYPE"] != "login") 
{
    if(strpos($GLOBALS["APPLICATION"]->GetCurDir(), "personal")===false)
    {
        $url = $GLOBALS["APPLICATION"]->GetCurPageParam("logout=yes", array("logout"));
    }else{
        $url = "/?logout=yes";
    }
    ?>
    <a href="<?=$arResult["urlToOwnProfile"]?>" class="view_form u_name"><?=$arResult["FULL_NAME"]?></a>
    <span class="separator">|</span>
    <a href="<?=$url?>" class="view_form exit"><?=GetMessage('AUTH_LOGOUT')?></a>
    <? 
} 
else 
{    
    ?>
    <div class="standart-form" data-module="signin-overlay">
		<h3 class="form-title standart-form__form-title"><?=GetMessage('AUTH_LOGIN_BUTTON')?></h3>
        
        <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/social-auth.php");?>
        
		<div class="divider"><span class="divider__span"><?=GetMessage('AUTH_OR')?></span></div>
        
		<form action="<?= $templateFolder ?>/ajax.php" method="POST" method="POST" id="auth-form" class="form" data-redirect="<?=$arParams["PROFILE_URL"]?>">
        	<div class="js-msg-block form__msg-block msg-block"></div>
            <input type="hidden" name="AUTH_FORM" value="Y" />
        	<input type="hidden" name="TYPE" value="AUTH" />
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <input type="hidden" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y"/>
            
            <?=bitrix_sessid_post()?>
            
            <div class="form__form-group email-container g-mt-15" autocomplete="off">
    	        <input data-validation="auth_login" data-type="adaptive-field" type="text" name="USER_LOGIN" class="form__form-control" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" autocomplete="off" value="<?=$arResult["USER_EMAIL"]?>">
    	    </div>
            
            <div class="form__form-group has-feedback">
    	        <input data-validation="auth_pass" data-type="password-field" type="password" name="USER_PASSWORD" class="form__form-control js-password-field" placeholder="<?=GetMessage('AUTH_PASSWORD')?>" autocomplete="off">
                <a href="#" data-type="password-show-toggle" class="password-show-toggle">
                    <span class="g-icon icon-password-eye"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
                </a>
    	    </div>
            
            <div class="form__form-actions">
    	        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
    	            <span class="init-state default-state"><?=GetMessage('AUTH_LOGIN_BUTTON')?></span>
    	            <span class="done-state"><?=GetMessage('AUTH_AUTHORIZING')?></span>
    	            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
    	            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
    	        </button>
    	        <a href="#" data-modal="restorePassURL" data-module="modal" data-type="openModal" class="form__subaction-link js-btnModalInit"><?=GetMessage('AUTH_RECOVERY_PASSWORD')?></a>
    	    </div>

		</form>
	</div>
    <?
}
?>