<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY) && htmlspecialcharsbx($_POST["TYPE"])=="AUTH" && check_bitrix_sessid()) 
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
         'message' => strip_tags($arResult['ERROR_MESSAGE']['MESSAGE']),
      ));
   } else {
      echo json_encode(array('type' => 'ok'));
   }
   require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
   die();
}

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
    <form action="<?=$arResult["AUTH_URL"]?>" method="POST" target="_top" id="login-form" class="signin-form" data-redirect="<?=$arParams["PROFILE_URL"]?>">
    	<input type="hidden" name="AUTH_FORM" value="Y" />
    	<input type="hidden" name="TYPE" value="AUTH" />
        <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
        <?=bitrix_sessid_post()?>
        
		<div class="form-group email-container" autocomplete="off">
			<label for="" class="sr-only">Эл. почта</label>
			<input type="email" name="USER_LOGIN" id="" class="form-control" value="<?=$arResult["USER_EMAIL"]?>" placeholder="Эл. почта" autocomplete="off"/>
		</div>
        
		<div class="form-group">
			<label for="" class="sr-only">Пароль</label>
			<input type="password" name="USER_PASSWORD" class="form-control" placeholder="Пароль">
		</div>
		<span class="divider"><span>или</span></span>
		<ul class="social-singin-list">
			<?/*<li><a href="#"><span data-icon="icon-ya-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-ok-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-gp-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-in-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-vk-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-tw-social"></span></a></li>
			<li><a href="#"><span data-icon="icon-im-social"></span></a></li>*/?>
            
            <li><a href="/social/?provider=yandex"><span data-icon="icon-ya-social"></span></a></li>
            <li><a href="/social/?provider=odnoklassniki"><span data-icon="icon-ok-social"></span></a></li>
            <li><a href="/social/?provider=google"><span data-icon="icon-gp-social"></span></a></li>
            <li><a href="/social/?provider=linkedin"><span data-icon="icon-in-social"></span></a></li>
            
            <li><a href="/social/?provider=vkontakte"><span data-icon="icon-vk-social"></span></a></li>
            <li><a href="/social/?provider=twitter"><span data-icon="icon-tw-social"></span></a></li>
            <li><a href="/social/?provider=instagram"><span data-icon="icon-im-social"></span></a></li>
			<li><a href="/social/?provider=facebook"><span data-icon="icon-fb-social"></span></a></li>
		</ul>
		<button type="submit" name="Login" class="btn btn-primary btn-block">Войти</button>
	</form>
    <?
}
?>