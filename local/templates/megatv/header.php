<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/header-code.php");
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH;?>/favicon.png">
		<link rel="icon" href="<?=SITE_TEMPLATE_PATH;?>/favicon.png">
        <?/*<meta name="author" content="http://hawkart.ru, разработка и поддержка интернет-проектов и информационных систем"/>*/?>
        <script id="globalConfig" type="text/x-config">
            {
                "authURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/auth.php",
                "registerURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/register.php",
                "restorePassURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/restore-password.php",
                "haveCodeRestorePassURL" : "<?=SITE_TEMPLATE_PATH;?>/ajax/modals/have-code-for-restore-pass.php",
                "sessid" : "<?=preg_replace("/sessid\=/","", bitrix_sessid_get());?>",
                "ajax_key" : "<?=md5('ajax_'.LICENSE_KEY)?>"
            }
        </script>
        <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/tmpl/js/vendor.js"></script><?
        
        if($APPLICATION->GetCurDir()=="/" || $APPLICATION->GetCurDir()=="/channels/"):
            ?><script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/tmpl/js/swiper.js"></script><?
        endif;
        
        $APPLICATION->ShowHead();
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/tmpl/css/main.css');
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/tmpl/css/project.css');
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/project.css');
        $APPLICATION->SetDirProperty("h1-hide", "hidden");
        ?>
        <meta name="verify-admitad" content="5e37e7b9c0" />
        
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

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
        <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body
        <?if($APPLICATION->GetCurDir()=="/personal/records/"):?> class="page-records"<?endif;?>
        <?if($APPLICATION->GetCurDir()=="/recommendations/"):?> class="page-recommendations"<?endif;?>
    >
        <div id="panel"><?$APPLICATION->ShowPanel();?></div>

		<div class="site-wrapper" data-module="page">

            <script type="text/x-config">
				{
					"bannersHideTime": 1,
					"pathToSVGSprite": "<?=SITE_TEMPLATE_PATH?>/tmpl/img/sprites/svg_sprite.svg",
					"playerURL": "<?=SITE_TEMPLATE_PATH?>/ajax/modals/player.php",
                    "playerLastPositionURL": "<?=SITE_TEMPLATE_PATH?>/ajax/player_last_position.php",
                    "shareURL": "<?=SITE_TEMPLATE_PATH?>/ajax/share.php",
                    "authentication" : <?=($USER->IsAuthorized()) ? "true" : "false"?>
                }
			</script>

	        <header class="site-wrapper__header header g-clearfix">
	            <div class="header__box-left box-left">
	                <div class="box-left__box-logo">
	                    <?/*if($APPLICATION->GetCurPage(false) === '/'):?>
						   <span class="box-logo__link-logo link-logo"><span class="link-logo__logo logo"></span></span>
	                    <?else:?>
	                        <a class="box-logo__link-logo link-logo" href="/"><span class="link-logo__logo logo"></span></a>
                        <?endif;*/?>
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
                    <?$APPLICATION->IncludeComponent("bitrix:menu", "top", Array(
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
                <section class="section-h1 <?/*if($APPLICATION->GetCurDir()=="/"):?> main-page__title<?endif;*/?><?$APPLICATION->ShowProperty("h1-hide");?>"><h1><?$APPLICATION->ShowProperty("h1");?></h1></section>