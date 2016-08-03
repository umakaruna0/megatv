<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/header-code.php");
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="shortcut icon" href="/favicon.png">
		<link rel="icon" href="/favicon.png">
		<meta name='yandex-verification' content='6b022e42074ebaca' />
        <?/*<meta name="author" content="http://hawkart.ru, разработка и поддержка интернет-проектов и информационных систем"/>*/?>
        <script id="globalConfig" type="text/x-config">
            {
                "authURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/auth.php",
                "registerURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/register.php",
                "restorePassURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/restore-password.php",
                "haveCodeRestorePassURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/have-code-for-restore-pass.php",
                "sessid" : "<?=preg_replace("/sessid\=/","",bitrix_sessid_get());?>",
                "ajax_key" : "<?=md5('ajax_'.LICENSE_KEY)?>"
            }
        </script>
        <?
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.(true ? ' /':'').'>'."\n";

        $APPLICATION->ShowMeta("robots", false, true);
        $APPLICATION->ShowMeta("keywords", false, true);
        $APPLICATION->ShowMeta("description", false, true);
        $APPLICATION->ShowCSS(true, true);

        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/tmpl/css/main.css');
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/project.css');
        
        $APPLICATION->ShowHeadStrings();
    	$APPLICATION->ShowHeadScripts();
        $APPLICATION->SetDirProperty("h1-hide", "hidden");
        ?>

		<title><?$APPLICATION->ShowTitle()?></title>
        <!-- Google Analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-75224044-1', 'auto');
          ga('send', 'pageview');
        
        </script>
        <!-- Google Analytics -->
	</head>
	<body
        <?if($APPLICATION->GetCurDir()=="/personal/records/"):?> class="page-records"<?endif;?>
        <?if($APPLICATION->GetCurDir()=="/recommendations/"):?> class="page-recommendations"<?endif;?>
    >
        <div id="panel"><?$APPLICATION->ShowPanel();?></div>

        <?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header-signin.php"), false);?>
        <? //require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/svg.php"); ?>

		<div class="site-wrapper" data-module="page">

            <script type="text/x-config">
				{
					"bannersHideTime": 1,
					"pathToSVGSprite": "<?=SITE_TEMPLATE_PATH?>/megatv/public/img/sprites/svg_sprite.svg",
					"playerURL": "<?=SITE_TEMPLATE_PATH?>/ajax/modals/player.php",
                    "playerLastPositionURL": "<?=SITE_TEMPLATE_PATH?>/ajax/player_last_position.php",
                    "shareURL": "<?=SITE_TEMPLATE_PATH?>/ajax/share.php",
                    "authentication" : <?=($USER->IsAuthorized()) ? "true" : "false"?>
                }
			</script>

	        <header class="site-wrapper__header header g-clearfix">
	            <div class="header__box-left box-left">
	                <div class="box-left__box-logo">
                        <?if(false):?>
    	                    <?if($APPLICATION->GetCurPage(false) === '/'):?>
    						   <span class="box-logo__link-logo link-logo"><span class="link-logo__logo logo"></span></span>
    	                    <?else:?>
    	                        <a class="box-logo__link-logo link-logo" href="/"><span class="link-logo__logo logo"></span></a>
                            <?endif;?>
	                    <?endif;?>
	                </div>
                    <?
                    	$APPLICATION->IncludeComponent("hawkart:city.list", "", Array(), false);
                        $APPLICATION->IncludeComponent("hawkart:lang.list", "", Array(), false);
                    ?>
	            </div>
	            <div class="header__box-right box-right">
                    <?if($APPLICATION->GetCurDir()!="/personal/records/"):?>
                        <div class="calendar-carousel" data-module="calendar-carousel">
                    		<script type="text/x-config">
                    			{
                    				"currentDate": "<?=CTimeEx::getCurDate()?>",
                                    "minDate": 1,
                                    "maxDate":<?=CTimeEx::getCalendarDays()?>
                    			}
                    		</script>
                    		<a href="#" class="prev-trigger disabled" data-type="prev-trigger"><span data-icon="icon-left-arrow-days"></span></a>
                    		<div class="dates-holder" data-type="dates-carousel"></div>
                    		<a href="#" class="next-trigger" data-type="next-trigger"><span data-icon="icon-right-arrow-days"></span></a>
                    	</div>
                    <?endif;?>
                    
                    <?$APPLICATION->IncludeComponent("hawkart:search", "", Array(), false);?>
                    <?$APPLICATION->IncludeComponent("bitrix:menu","top", Array(
                            "ROOT_MENU_TYPE" => "top_".LANGUAGE_ID, 
                            "MAX_LEVEL" => "1", 
                            "CHILD_MENU_TYPE" => "top_".LANGUAGE_ID, 
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "Y",
                            "MENU_CACHE_TYPE" => "N", 
                            "MENU_CACHE_TIME" => "3600", 
                            "MENU_CACHE_USE_GROUPS" => "Y", 
                            "MENU_CACHE_GET_VARS" => "" 
                        )
                    );?>
                    
                    <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/header-user-card.php");?>

	             <!--    <div class="box-right__box-menu">
	                    <a class="box-menu__link" href="/channels/"><span class="box-menu__icon g-icon"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-channels"></use></svg></span><span class="box-menu__title">Каналы</span></a>
	                    <a class="box-menu__link" href="/recommendations/"><span class="box-menu__icon g-icon"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recommendations"></use></svg></span><span class="box-menu__title">Рекомендации</span></a>
	                </div> -->
	                <!-- <div class="box-userbar box-right__box-userbar">
	                    <a href="<?=SITE_TEMPLATE_PATH."/ajax/tmpl/";?>auth" class="g-btn g-btn--primary box-userbar__btn-auth js-btnModalInit"><span>Войти</span></a>
	                    <a href="<?=SITE_TEMPLATE_PATH."/ajax/tmpl/";?>register" class="g-btn box-userbar__btn-register js-btnModalInit"><span>Зарегистрироваться</span></a>
	                </div> -->
	            </div>
	        </header>
	        <div class="ModalWindow js-ModalWindow">
	            <div class="ModalWindow__overlay js-ModalOverlay">
	                <div class="ModalWindow__content js-ModalContent">
	                    <div class="ModalWindow__loader"></div>
	                </div>
	            </div>
	            <div class="ModalWindow__blueBackground" data-type="closeModal"></div>
	        </div>
            

			<main class="site-content">
                <section class="section-h1 <?$APPLICATION->ShowProperty("h1-hide");?>"><h1><?$APPLICATION->ShowProperty("h1");?></h1></section>