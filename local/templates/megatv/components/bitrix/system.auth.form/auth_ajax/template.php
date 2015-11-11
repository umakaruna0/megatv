<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/*if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY) && htmlspecialcharsbx($_POST["TYPE"])=="AUTH" && check_bitrix_sessid()) 
{
   $APPLICATION->RestartBuffer();
   if (!defined('PUBLIC_AJAX_MODE')) 
   {
      define('PUBLIC_AJAX_MODE', true);
   }
   header('Content-type: application/json');
   if ($arResult['ERROR']) 
   {
      echo json_encode(array(
         'type' => 'error',
         'status' => 'error',
         'message' => strip_tags($arResult['ERROR_MESSAGE']['MESSAGE']),
      ));
   } else {
      echo json_encode(array('type' => 'ok', 'status' => 'ok'));
   }
   require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
   die();
}*/

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
    <a href="<?=$url?>" class="view_form exit">Выйти</a>
    <? 
} 
else 
{    
    ?>
    <div class="authorize-overlay is-signin-overlay" data-module="signin-overlay">
		<div class="overlay-content">
			<h4 class="overlay-title">Войти</h4>
            
            <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/social-auth.php");?>
            
			<span class="divider"><span>или</span></span>
            
			<form action="<?= $templateFolder ?>/ajax.php" method="POST" target="_top" id="login-form" class="signin-form" data-redirect="<?=$arParams["PROFILE_URL"]?>">
            	<input type="hidden" name="AUTH_FORM" value="Y" />
            	<input type="hidden" name="TYPE" value="AUTH" />
                <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                <?=bitrix_sessid_post()?>
                
        		<div class="form-group email-container" autocomplete="off">
        			<label for="" class="sr-only">Телефон или эл. почта</label>
        			<input type="text" name="USER_LOGIN" class="form-control" value="<?=$arResult["USER_EMAIL"]?>" placeholder="Телефон или эл. почта" autocomplete="off" data-type="adaptive-field" />
        		</div>
                
				<div class="form-group has-feedback" data-type="password-field-group">
					<label for="" class="sr-only">Пароль</label>
                    <input type="password" name="USER_PASSWORD" class="form-control" placeholder="Пароль" data-type="password-field" autocomplete="off">
					<input type="text" class="form-control" data-type="password-visualizer" placeholder="Пароль">
					<span class="form-control-feedback">
						<a href="#" data-type="password-visibility-toggle"><span data-icon="icon-password-eye"></span></a>
					</span>
				</div>
                
				<div class="form-actions">
					<button type="submit" name="Login" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
						<span class="default-state init-state">Войти</span>
						<span class="done-state">Авторизую вас...</span>
						<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
						<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
					</button>
					<a href="#" class="form-subaction-link" data-type="reset-handler-link">Восстановить пароль</a>
				</div>
			</form>
		</div>
	</div>
    <?
}
?>