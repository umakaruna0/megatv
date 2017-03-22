<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
            </main>
			<footer class="site-footer">
                <?$APPLICATION->ShowViewContent("channel_footer_desc");?>
				<div class="footer-content">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/".LANGUAGE_ID."/footer-copyright.php"), false);?>
				</div>
			</footer>
			<div class="drop-overlay"></div>
		</div><!-- /.site-wrapper -->
        
        <?
        if($APPLICATION->GetCurDir() == "/personal/"){
            $js = array(
                "tmpl/js/user-profile.js"
            );
        }elseif($APPLICATION->GetCurDir() == "/personal/records/"){
            $js = array(
                "tmpl/js/user-records.js",
            );
        }elseif($APPLICATION->GetCurDir() == "/personal/services/"){
            $js = array(
                "tmpl/js/user-services.js"
            );
        }
        elseif(strpos($APPLICATION->GetCurDir(), "/channels/")!==false && !empty($_REQUEST["SCHEDULE_CODE"])){
            $js = array(
                "tmpl/js/broadcast-card.js",
            );
        }elseif(strpos($APPLICATION->GetCurDir(), "/channels/")!==false && !empty($_REQUEST["CHANNEL_CODE"])){
            $js = array(
                "tmpl/js/channel-card.js",
            );
        }else{
            $js = array(
                "tmpl/js/main.js"
            );
        }
        if(!$USER->IsAuthorized())
        {
            $js = array(
                "tmpl/js/main.js"
            );
        }
        $js[] = "tmpl/js/player.js";
        $js[] = "tmpl/js/project.js";
        
        foreach($js as $path)
        {
            $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/".$path);
        }
        
        global $USER;
        //if($USER->IsAdmin()){
        //$site_quide = $APPLICATION->get_cookie("SITE_GUIDE");
        //if($site_quide!="Y" && !$USER->IsAuthorized())
        //{
            ?>
            <?/*<link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.3.0/introjs.min.css" rel="stylesheet">
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.3.0/intro.min.js"></script>*/?>
            <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/tmpl/js/introjs.js"></script>
            <script>
            /*$(document).ready(function()
            {
                var intro = introJs();
                intro.setOptions({
                    steps: [
                        {
                            intro: "Добро пожаловать на сайта МегаТВ!<br /> Это тур по сайту, который ознакомит вас с функционалом сайта."
                        },
                        {
                            element: '#mod-user-navigation-1',
                            intro: "Зарегистрируйтесь и записывайте любимые шоу в персональное облачное хранилище.",
                            position: 'left'
                        },
                        {
                            intro: '<span class="item-status-icon"><div class="icon icon-recordit "><svg class="icon__cnt"><use xlink:href="#icon-recordit"></use></svg></div></span>Для того, чтобы записать программу, нажмите на кнопку. Она появится при наведение на интересующую вас передачу.',
                            position: 'auto'
                        },
                        {
                            intro: '<span class="badge">в эфире</span><br /> Для просмотра канала в реальном времени нажмите на кнопку "В эфире".'
                        },
                        {
                            element: '.swiper-button-next',
                            intro: "Для навигации по расписанию передач используйте стрелочки.",
                            position: 'left'
                        },
                        {
                            element: 'a.box-menu__link[href="/recommendations/"]',
                            intro: 'Чем больше передач Вы записываете в облачное<br /> хранилище - тем более точными становятся наши персональные рекомендации.',
                            position: 'bottom-middle-aligned'
                        },
                        {
                            element: '#mod-search-1',
                            intro: 'Воспользуйтесь поиском и найдите свою любимую передачу!',
                            position: 'bottom-middle-aligned'
                        },
                        
                    ],
                    keyboardNavigation: true,
                    disableInteraction: true,
                    nextLabel: 'След. шаг',
                    prevLabel: 'Пред. шаг',
                    skipLabel: 'Пропустить',
                    doneLabel: 'Завершить'
                }).onbeforechange(function(targetElement) {
                    
                    if($(targetElement).hasClass("item-status-icon"))
                    {
                        $(targetElement).show();
                    }
                    console.log(targetElement);
                });
                
                intro.start();
            })*/
            </script>
            <?
            $APPLICATION->set_cookie("SITE_GUIDE", "Y");  
        //}
        //}    
        ?>
        
        <?if(intval($_REQUEST["record_id"])>0 && $_REQUEST["play"]=="yes"):?>
            <script>
                Box.Application.broadcast('playbroadcast', {
        			broadcastID: <?=intval($_REQUEST["record_id"])?>,
        			record: true
        		});
            </script>
        <?endif;?>
                
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter36131600 = new Ya.Metrika({
                            id:36131600,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true
                        });
                    } catch(e) { }
                });
        
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";
        
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/36131600" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
        
        <!-- Facebook social -->
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '1710346209200604',
              xfbml      : true,
              version    : 'v2.5'
            });
          };
        
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/en_US/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- Facebook social -->
	</body>
</html>