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
                "tmpl/js/player.js"
            );
        }elseif($APPLICATION->GetCurDir() == "/personal/services/"){
            $js = array(
                "tmpl/js/user-services.js"
            );
        }
        elseif(strpos($APPLICATION->GetCurDir(), "/channels/")!==false && isset($_REQUEST["SCHEDULE_CODE"])){
            $js = array(
                "tmpl/js/broadcast-card.js",
                "tmpl/js/player.js",
                "project.js",
            );
        }elseif(strpos($APPLICATION->GetCurDir(), "/channels/")!==false && !empty($_REQUEST["CHANNEL_CODE"])){
            $js = array(
                "tmpl/js/channel-card.js",
                "tmpl/js/player.js"
            );
        }else{
            $js = array(
                "tmpl/js/main.js"
            );
        }
        foreach($js as $path)
        {
            ?><script src="<?=SITE_TEMPLATE_PATH?>/<?=$path?>"></script><?
        }
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